<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Làm bài thi: ') . $exam->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="border border-gray-200 rounded-lg px-4 py-2">
                        <p class="text-xs text-gray-600">Thời gian còn lại</p>
                        <div id="timer" class="text-lg font-semibold text-gray-900">--:--:--</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="pause-timer" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Tạm dừng</button>
                        <button type="button" id="resume-timer" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Tiếp tục</button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-sm text-gray-800">
                        <input type="checkbox" id="lock-camera" class="border-gray-400"> Khóa camera khi làm bài
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button" id="toggle-camera" class="inline-flex items-center px-3 py-2 bg-white border border-black rounded-md text-xs font-semibold text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">Bật camera</button>
                        <span id="camera-status" class="text-xs text-gray-600">Camera: Tắt</span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <video id="camera-preview" class="w-48 h-36 bg-gray-100 border border-gray-200 rounded-lg hidden" autoplay playsinline muted></video>
            </div>

            <form id="exam-form" class="space-y-6" method="POST" action="{{ route('student.quiz.submit', $exam) }}">
                @csrf
                <input type="hidden" name="answers" id="answers-input">
                <input type="hidden" name="remaining_seconds" id="remaining-seconds-input">

                @foreach($questions as $index => $question)
                    @include('student.quiz.partials.question', [
                        'exam' => $exam,
                        'question' => $question,
                        'index' => $index + 1,
                        'savedAnswer' => $savedAnswers[$question->id] ?? null
                    ])
                @endforeach

                <div class="flex items-center justify-end gap-3">
                    <button type="button" id="save-draft" class="inline-flex items-center px-4 py-2 bg-white border border-black rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-black hover:text-white focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                        Lưu nháp
                    </button>
                    <button type="submit" id="submit-exam" class="inline-flex items-center px-4 py-2 bg-black border border-black rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 transition">
                        Nộp bài
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script>
        const examId = {{ $exam->id }};
        const durationMinutes = {{ $durationMinutes }};
        let remaining = {{ $session->remaining_seconds ?? ($durationMinutes * 60) }};
        let timerId = null;
        let isPaused = false;

        function renderTimer() {
            const h = Math.floor(remaining / 3600).toString().padStart(2, '0');
            const m = Math.floor((remaining % 3600) / 60).toString().padStart(2, '0');
            const s = Math.floor(remaining % 60).toString().padStart(2, '0');
            const t = document.getElementById('timer');
            if (t) t.textContent = `${h}:${m}:${s}`;
        }

        function tick() {
            if (!isPaused) {
                remaining = Math.max(0, remaining - 1);
                renderTimer();
                if (remaining === 0) {
                    clearInterval(timerId);
                    timerId = null;
                    submitExam();
                }
            }
        }

        document.getElementById('pause-timer')?.addEventListener('click', () => {
            isPaused = true;
        });

        document.getElementById('resume-timer')?.addEventListener('click', () => {
            isPaused = false;
        });

        timerId = setInterval(tick, 1000);
        renderTimer();

        function serializeAnswers() {
            const data = {};
            document.querySelectorAll('[data-question-id]').forEach(qEl => {
                const qId = qEl.getAttribute('data-question-id');
                const qType = qEl.getAttribute('data-question-type');
                
                switch(qType) {
                    case 'single':
                    case 'boolean':
                        const radio = qEl.querySelector(`input[name="q_${qId}"]:checked`);
                        data[qId] = radio ? radio.value : null;
                        break;
                    case 'multi':
                        const checkboxes = Array.from(qEl.querySelectorAll(`input[name="q_${qId}[]"]:checked`)).map(c => c.value);
                        data[qId] = checkboxes;
                        break;
                    case 'text':
                        const textInput = qEl.querySelector(`input[name="q_${qId}"]`);
                        data[qId] = textInput ? textInput.value : '';
                        break;
                    case 'order':
                        const orderList = qEl.querySelector('[data-order-list]');
                        if (orderList) {
                            data[qId] = Array.from(orderList.children).map(li => li.getAttribute('data-value'));
                        }
                        break;
                    case 'match':
                        const pairsInput = qEl.querySelector(`#pairs_${qId}`);
                        if (pairsInput && pairsInput.value) {
                            try {
                                data[qId] = JSON.parse(pairsInput.value);
                            } catch (e) {
                                data[qId] = {};
                            }
                        } else {
                            data[qId] = {};
                        }
                        break;
                    case 'essay':
                        const textarea = qEl.querySelector(`textarea[name="q_${qId}"]`);
                        data[qId] = textarea ? textarea.value : '';
                        break;
                }
            });
            return data;
        }

        document.getElementById('save-draft')?.addEventListener('click', () => {
            const answers = serializeAnswers();
            const formData = new FormData();
            formData.append('answers', JSON.stringify(answers));
            formData.append('remaining_seconds', remaining);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/student/quiz/${examId}/draft`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'HX-Request': 'true'
                }
            })
            .then(r => r.text())
            .then(html => {
                const indicator = document.createElement('div');
                indicator.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                indicator.textContent = 'Đã lưu nháp';
                document.body.appendChild(indicator);
                setTimeout(() => indicator.remove(), 2000);
            });
        });

        document.getElementById('exam-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const answers = serializeAnswers();
            document.getElementById('answers-input').value = JSON.stringify(answers);
            document.getElementById('remaining-seconds-input').value = remaining;
            this.submit();
        });

        let stream = null;
        const preview = document.getElementById('camera-preview');
        const toggleBtn = document.getElementById('toggle-camera');
        const statusEl = document.getElementById('camera-status');
        const lockCam = document.getElementById('lock-camera');

        async function enableCam() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                preview.srcObject = stream;
                preview.classList.remove('hidden');
                if (statusEl) statusEl.textContent = 'Camera: Bật';
                if (toggleBtn) toggleBtn.textContent = 'Tắt camera';
            } catch (e) {
                alert('Không thể bật camera.');
            }
        }

        function disableCam() {
            if (stream) stream.getTracks().forEach(t => t.stop());
            stream = null;
            if (preview) preview.srcObject = null;
            preview.classList.add('hidden');
            if (statusEl) statusEl.textContent = 'Camera: Tắt';
            if (toggleBtn) toggleBtn.textContent = 'Bật camera';
        }

        toggleBtn?.addEventListener('click', () => {
            if (stream) disableCam(); else enableCam();
        });

        function submitExam() {
            if (lockCam?.checked && !stream) {
                alert('Bạn cần bật camera để nộp bài.');
                return;
            }
            document.getElementById('exam-form').submit();
        }

        document.getElementById('submit-exam')?.addEventListener('click', (e) => {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn nộp bài?')) {
                submitExam();
            }
        });

        setInterval(() => {
            const answers = serializeAnswers();
            const formData = new FormData();
            formData.append('answers', JSON.stringify(answers));
            formData.append('remaining_seconds', remaining);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/student/quiz/${examId}/draft`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'HX-Request': 'true'
                }
            });
        }, 30000);
    </script>
</x-app-layout>


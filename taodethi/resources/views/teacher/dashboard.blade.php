<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-900 leading-tight">
			{{ __('Bảng điều khiển giáo viên') }}
		</h2>
	</x-slot>

	<div class="py-8">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<a href="{{ route('teacher.questions') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Quản lý ngân hàng câu hỏi</h3>
					<p class="text-sm text-gray-700 mt-2">CRUD câu hỏi, tag, độ khó, kỹ năng, phiên bản, import/export.</p>
				</a>
				<a href="{{ route('teacher.exams') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Tạo đề/đề thi tự động</h3>
					<p class="text-sm text-gray-700 mt-2">Cấu trúc theo chủ đề, tỉ lệ độ khó, điểm, random hoá.</p>
				</a>
				<a href="{{ route('teacher.grading') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Chấm thi</h3>
					<p class="text-sm text-gray-700 mt-2">Tự chấm trắc nghiệm, review tự luận theo rubric.</p>
				</a>
				<a href="{{ route('analytics.personal') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Analytics cá nhân</h3>
					<p class="text-sm text-gray-700 mt-2">Điểm theo kỹ năng, tiến bộ theo thời gian, gợi ý ôn tập.</p>
				</a>
				<a href="{{ route('analytics.class') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Analytics lớp / trường</h3>
					<p class="text-sm text-gray-700 mt-2">Phân bố điểm, item analysis, chỉ số độ khó & phân biệt.</p>
				</a>
				<a href="{{ route('teacher.ai.generator') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">AI: Sinh câu hỏi</h3>
					<p class="text-sm text-gray-700 mt-2">Từ tài liệu (PDF, slides), MCQ/Đúng-Sai, distractors.</p>
				</a>
				<a href="{{ route('teacher.ai.essay_grading') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">AI: Chấm tự luận nâng cao</h3>
					<p class="text-sm text-gray-700 mt-2">Rubric, similarity check, gợi ý chấm.</p>
				</a>
				<a href="{{ route('teacher.schedules') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Lịch thi / Mở-đóng đề</h3>
					<p class="text-sm text-gray-700 mt-2">Quản lý thời gian, thời lượng, số lần làm.</p>
				</a>
				<a href="{{ route('teacher.classes') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Quản lý lớp & học viên</h3>
					<p class="text-sm text-gray-700 mt-2">Import học sinh, phân nhóm, trợ giảng.</p>
				</a>
				<a href="{{ route('admin.security') }}" class="block bg-white border border-gray-200 rounded-xl p-6 hover:shadow-sm transition">
					<h3 class="text-lg font-semibold text-gray-900">Bảo mật & chống gian lận</h3>
					<p class="text-sm text-gray-700 mt-2">Camera/tab/fullscreen, IP logging, token (UI).</p>
				</a>
			</div>
		</div>
	</div>
</x-app-layout>

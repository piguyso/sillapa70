<div class="flex items-center justify-between gap-4 border-t first:border-t-0 py-3">
 <span class="font-bold">{{ $label }}</span>
 <form method="POST" action="{{ route('systemactive.rule.update') }}" class="access-toggle-form shrink-0" data-rule-key="{{ $level }}|{{ $subject }}|{{ $scope }}|{{ $scopeId }}" @isset($parentKey) data-parent-key="{{ $parentKey }}" @endisset>
  @csrf @method('PUT')
  <input type="hidden" name="competition_level" value="{{ $level }}"><input type="hidden" name="subject_type" value="{{ $subject }}"><input type="hidden" name="scope_type" value="{{ $scope }}"><input type="hidden" name="scope_id" value="{{ $scopeId }}"><input type="hidden" name="is_closed" value="{{ $isClosed ? 0 : 1 }}">
  <x-form.button type="submit" :variant="$isClosed ? 'danger' : 'primary'" class="access-toggle-button min-w-44" style="background-color:{{ $isClosed ? '#dc2626' : '#16a34a' }};color:#fff"><span class="access-toggle-dot inline-block w-2.5 h-2.5 rounded-full mr-2 {{ $isClosed ? 'bg-red-200' : 'bg-emerald-200' }}"></span><span class="access-toggle-label">{{ $isClosed ? 'ล๊อคไม่ให้ใช้งาน' : 'ใช้งานได้' }}</span></x-form.button>
 </form>
</div>

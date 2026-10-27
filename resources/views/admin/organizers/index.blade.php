@extends('layouts.app', ['title' => 'Quản Lý Đối Tác - MEGATIX Admin'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-1.5"></i> Quay lại Admin Dashboard
            </a>
            <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center">
                <i class="fa-solid fa-handshake text-purple-400 mr-3"></i> Quản Lý & Xét Duyệt Đối Tác (Organizer)
            </h1>
        </div>
    </div>

    <div class="bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-xl">
        @if($profiles->isEmpty())
            <div class="text-center py-12 text-slate-500 text-xs">
                Chưa có đơn xin đăng ký làm đối tác nào.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase bg-slate-950/60 text-slate-400 border-b border-slate-800">
                        <tr>
                            <th class="py-3 px-4">Tên Công ty / Đơn vị</th>
                            <th class="py-3 px-4">Tài khoản User</th>
                            <th class="py-3 px-4">Số điện thoại</th>
                            <th class="py-3 px-4">Ghi chú</th>
                            <th class="py-3 px-4 text-center">Trạng thái</th>
                            <th class="py-3 px-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($profiles as $p)
                            <tr class="hover:bg-slate-800/40 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-white">
                                    {{ $p->company_name }}
                                </td>
                                <td class="py-3.5 px-4 text-xs font-mono text-purple-300">
                                    {{ $p->user->name }}
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    {{ $p->phone }}
                                </td>
                                <td class="py-3.5 px-4 text-xs text-slate-400 max-w-xs truncate" title="{{ $p->notes }}">
                                    {{ $p->notes ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($p->status === 'approved')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Đã duyệt
                                        </span>
                                    @elseif($p->status === 'pending')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                            <i class="fa-solid fa-clock mr-1"></i> Chờ duyệt
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                                            <i class="fa-solid fa-circle-xmark mr-1"></i> Từ chối
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-2 whitespace-nowrap">
                                    @if($p->status === 'pending')
                                        <form action="{{ route('admin.organizers.approve', $p->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-colors">
                                                Phê Duyệt
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.organizers.reject', $p->id) }}" method="POST" class="inline" onsubmit="return confirmReject(this)">
                                            @csrf
                                            <input type="hidden" name="notes" value="">
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-950/60 border border-rose-500/40 text-rose-300 hover:bg-rose-900/60 text-xs font-bold transition-colors">
                                                Từ Chối
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-500 italic">Đã xử lý</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $profiles->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    function confirmReject(form) {
        const reason = prompt('Vui lòng nhập lý do từ chối:');
        if (reason === null) return false;
        form.querySelector('input[name="notes"]').value = reason;
        return true;
    }
</script>
@endsection

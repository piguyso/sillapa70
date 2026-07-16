@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
        <div class="mx-auto h-16 w-16 bg-primary rounded-full flex items-center justify-center shadow-lg mb-4">
            <i class="fas fa-user-lock text-3xl text-white"></i>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900">
            เข้าสู่ระบบ
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            งานศิลปหัตถกรรมนักเรียน
        </p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl rounded-xl sm:px-10 border border-gray-100">
            
            <form class="space-y-6" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Username
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input id="email" name="email" type="text" autocomplete="username" required autofocus
                            class="appearance-none block w-full pl-10 pr-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm transition duration-150"
                            value="{{ old('email') }}" placeholder="กรอกชื่อผู้ใช้งาน">
                    </div>
                    @if ($errors->has('email'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first('email') }}
                        </p>
                    @endif
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="appearance-none block w-full pl-10 pr-3 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm transition duration-150"
                            placeholder="••••••••">
                    </div>
                    @if ($errors->has('password'))
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>

                <div class="space-y-4 pt-2">
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-primary hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-sign-in-alt mr-2 mt-1"></i> เข้าสู่ระบบ
                    </button>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                หรือ
                            </span>
                        </div>
                    </div>

                    <a href="{{route('myreg.form')}}" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-secondary hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-user-plus mr-2 mt-1"></i> ลงทะเบียนผู้ใช้งานใหม่
                    </a>
                </div>
            </form>
        </div>
        
        <p class="mt-6 text-center text-xs text-gray-400">
            &copy; 2026 ระบบงานศิลปหัตถกรรมนักเรียน
        </p>
    </div>
</div>
@endsection
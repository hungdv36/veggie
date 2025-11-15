@extends('layouts.app')
@section('content')
<div class="chatbot-popup">
    <div class="chat-header">
        <div class="header-info">
            <svg class="chatbot-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 0a12 12 0 1012 12A12 12 0 0012 0zm0 22a10 10 0 1110-10 10 10 0 01-10 10z"/></svg>
            <span class="logo-text">AI Chatbot</span>
        </div>
        <button id="close-chatbot">×</button>
    </div>
    <div class="chat-body"></div>
    <div class="chat-footer">
        <form class="chat-form">
            <textarea class="message-input" placeholder="Nhập tin nhắn..."></textarea>
            <div class="chat-controls">
                <button type="button" id="send-message">➤</button>
            </div>
        </form>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
<script src="{{ asset('js/chatbot.js') }}"></script>
@endsection

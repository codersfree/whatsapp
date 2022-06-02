<div x-data="data()" class="bg-gray-50 rounded-lg shadow border border-gray-200 overflow-hidden">
    

    <div class="grid grid-cols-3 divide-x divide-gray-200">

        <div class="col-span-1">

            <div class="bg-gray-100 h-16 flex items-center px-4">

                <img class="w-10 h-10 object-cover object-center" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                
            </div>

            <div class="bg-white h-14 flex items-center px-4">
                
                <x-jet-input type="text"
                    wire:model="search"
                    class="w-full" 
                    placeholder="Busque un chat o inicie uno nuevo"/>

            </div>

            <div class="h-[calc(100vh-10.5rem)] overflow-auto border-t border-gray-200">
                @if ($this->chats->count() == 0 || $search)
                
                    <div class="px-4 py-3">
                        <h2 class="text-teal-600 text-lg mb-4">Contáctos</h2>
                    

                        <ul class="space-y-4">
                            @forelse ($this->contacts as $contact)
                                <li class="cursor-pointer" wire:click="open_chat_contact({{$contact}})">
                                    <div class="flex">

                                        <figure class="flex-shrink-0">
                                            <img class="h-12 w-12 object-cover object-center rounded-full" src="{{ $contact->user->profile_photo_url }}" alt="{{$contact->name}}">
                                        </figure>


                                        <div class="flex-1 ml-5 border-b border-gray-200">
                                            <p class="text-gray-800">
                                                {{ $contact->name }}
                                            </p>
                                            <p class="text-gray-600 text-xs">
                                                {{ $contact->user->email }}
                                            </p>
                                        </div>

                                    </div>
                                </li>
                            @empty
                                
                            @endforelse
                        </ul>
                        
                    </div>

                @else
                    
                    @foreach ($this->chats as $chatItem)
                        
                        <div wire:key="chats-{{ $chatItem->id }}"
                            wire:click="open_chat({{$chatItem}})"
                            class="flex items-center justify-between {{ $chat && $chat->id == $chatItem->id ? 'bg-gray-200' : 'bg-white' }} hover:bg-gray-100 cursor-pointer px-3">

                            <figure>
                                <img src="{{$chatItem->image}}" class="h-12 w-12 object-cover object-center rounded-full" alt="{{ $chatItem->name }}">
                            </figure>

                            <div class="w-[calc(100%-4rem)] py-4 border-b border-gray-200">
                                <div class="flex justify-between items-center">

                                    <div>

                                        <p>
                                            {{ $chatItem->name }}
                                        </p>

                                        <p class="text-sm text-gray-700 mt-1 truncate">
                                            {{ $chatItem->messages->last()->body }}
                                        </p>

                                    </div>

                                    <div class="text-right">
                                        <p class="text-xs">
                                            {{ $chatItem->last_message_at->format('h:i A') }}
                                        </p>

                                        @if ($chatItem->unread_messages)
                                            
                                            <span class="inline-flex items-center justify-center px-2 py-1 mr-2 text-xs font-bold leading-none text-green-100 bg-green-600 rounded-full">
                                                {{ $chatItem->unread_messages }}
                                            </span>

                                        @endif
                                    </div>

                                </div>

                                

                            </div>

                        </div>

                    @endforeach


                @endif
            </div>

        </div>



        <div class="col-span-2">

            @if ($contactChat || $chat)

                <div class="bg-gray-100 h-16 flex items-center px-3">

                    <figure>

                        @if ($chat)
                            <img class="w-10 h-10 rounded-full object-cover object-center" src="{{$chat->image}}" alt="{{ $chat->name }}">
                        @else
                            <img class="w-10 h-10 rounded-full object-cover object-center" src="{{$contactChat->user->profile_photo_url}}" alt="{{ $contactChat->name }}">                            
                        @endif

                    </figure>

                    <div class="ml-4">
                        <p class="text-gray-800">

                            @if ($chat)
                                {{ $chat->name }}
                            @else
                                {{ $contactChat->name }}                                
                            @endif

                        </p>
                        <p class="text-gray-600 text-xs" x-show="chat_id == typingChatId">
                            Escribiendo ...
                        </p>

                        @if ($this->active)
                            
                            <p class="text-green-500 text-xs" x-show="chat_id != typingChatId" wire:key="online">
                                Online
                            </p>

                        @else

                            <p class="text-red-600 text-xs" x-show="chat_id != typingChatId" wire:key="offline">
                                Offline
                            </p>

                        @endif

                    </div>

                </div>

                <div class="h-[calc(100vh-11rem)] px-3 py-2 overflow-auto">
                    {{-- El contenido de nuestro chat --}}
                    @foreach ($this->messages as $message)
                        
                        <div class="flex {{ $message->user_id == auth()->id() ? 'justify-end' : '' }} mb-2">

                            <div class="rounded px-3 py-2 {{ $message->user_id == auth()->id() ? 'bg-green-100' : 'bg-gray-200' }}">
                                <p class="text-sm">
                                    {{$message->body}}
                                </p>

                                <p class="{{ $message->user_id == auth()->id() ? 'text-right' : '' }} text-xs text-gray-600 mt-1">
                                    {{$message->created_at->format('d-m-y h:i A')}}

                                    @if ($message->user_id == auth()->id())
                                        <i class="fas fa-check-double ml-2 {{ $message->is_read ? 'text-blue-500' : 'text-gray-600' }}"></i>
                                    @endif
                                </p>
                            </div>

                        </div>

                    @endforeach

                    <span id="final"></span>

                </div>

                <form class="bg-gray-100 h-16 flex items-center px-4" wire:submit.prevent="sendMessage()">

                    <x-jet-input wire:model="bodyMessage" type="text" class="flex-1" placeholder="Escriba un mensaje aquí" />

                    <button class="flex-shrink-0 ml-4 text-2xl text-gray-700">
                        <i class="fas fa-share"></i>
                    </button>

                </form>

            @else

                <div class="w-full h-full flex justify-center items-center">

                    <div>

                        <div class="WM0_u" style="transform: scale(1); opacity: 1;"><span data-testid="intro-md-beta-logo-light" data-icon="intro-md-beta-logo-light" class="IVxyB"><svg width="360" viewBox="0 0 303 172" fill="none" preserveAspectRatio="xMidYMid meet" class=""><path fill-rule="evenodd" clip-rule="evenodd" d="M229.565 160.229c32.647-10.984 57.366-41.988 53.825-86.81-5.381-68.1-71.025-84.993-111.918-64.932C115.998 35.7 108.972 40.16 69.239 40.16c-29.594 0-59.726 14.254-63.492 52.791-2.73 27.933 8.252 52.315 48.89 64.764 73.962 22.657 143.38 13.128 174.928 2.513Z" fill="#DAF7F3"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M131.589 68.942h.01c6.261 0 11.336-5.263 11.336-11.756S137.86 45.43 131.599 45.43c-5.081 0-9.381 3.466-10.822 8.242a7.302 7.302 0 0 0-2.404-.405c-4.174 0-7.558 3.51-7.558 7.838s3.384 7.837 7.558 7.837h13.216ZM105.682 128.716c3.504 0 6.344-2.808 6.344-6.27 0-3.463-2.84-6.27-6.344-6.27-1.156 0-2.24.305-3.173.839v-.056c0-6.492-5.326-11.756-11.896-11.756-5.29 0-9.775 3.413-11.32 8.132a8.025 8.025 0 0 0-2.163-.294c-4.38 0-7.93 3.509-7.93 7.837 0 4.329 3.55 7.838 7.93 7.838h28.552Z" fill="#fff"></path><rect x=".445" y=".55" width="50.58" height="100.068" rx="7.5" transform="rotate(6 -391.775 121.507) skewX(.036)" fill="#42CBA5" stroke="#316474"></rect><rect x=".445" y=".55" width="50.403" height="99.722" rx="7.5" transform="rotate(6 -356.664 123.217) skewX(.036)" fill="#fff" stroke="#316474"></rect><path d="m57.16 51.735-8.568 82.024a5.495 5.495 0 0 1-6.042 4.895l-32.97-3.465a5.504 5.504 0 0 1-4.897-6.045l8.569-82.024a5.496 5.496 0 0 1 6.041-4.895l5.259.553 22.452 2.36 5.259.552a5.504 5.504 0 0 1 4.898 6.045Z" fill="#EEFEFA" stroke="#316474"></path><path d="M26.2 102.937c.863.082 1.732.182 2.602.273.238-2.178.469-4.366.69-6.546l-2.61-.274c-.238 2.178-.477 4.365-.681 6.547Zm-2.73-9.608 2.27-1.833 1.837 2.264 1.135-.917-1.838-2.266 2.27-1.833-.92-1.133-2.269 1.834-1.837-2.264-1.136.916 1.839 2.265-2.27 1.835.92 1.132Zm-.816 5.286c-.128 1.3-.265 2.6-.41 3.899.877.109 1.748.183 2.626.284.146-1.31.275-2.614.413-3.925-.878-.092-1.753-.218-2.629-.258Zm16.848-8.837c-.506 4.801-1.019 9.593-1.516 14.396.88.083 1.748.192 2.628.267.496-4.794 1-9.578 1.513-14.37-.864-.143-1.747-.192-2.625-.293Zm-4.264 2.668c-.389 3.772-.803 7.541-1.183 11.314.87.091 1.74.174 2.601.273.447-3.912.826-7.84 1.255-11.755-.855-.15-1.731-.181-2.589-.306-.04.156-.069.314-.084.474Zm-4.132 1.736c-.043.159-.06.329-.077.49-.297 2.896-.617 5.78-.905 8.676l2.61.274c.124-1.02.214-2.035.33-3.055.197-2.036.455-4.075.627-6.115-.863-.08-1.724-.17-2.585-.27Z" fill="#316474"></path><path d="M17.892 48.489a1.652 1.652 0 0 0 1.468 1.803 1.65 1.65 0 0 0 1.82-1.459 1.652 1.652 0 0 0-1.468-1.803 1.65 1.65 0 0 0-1.82 1.459ZM231.807 136.678l-33.863 2.362c-.294.02-.54-.02-.695-.08a.472.472 0 0 1-.089-.042l-.704-10.042a.61.61 0 0 1 .082-.054c.145-.081.383-.154.677-.175l33.863-2.362c.294-.02.54.02.695.08.041.016.069.03.088.042l.705 10.042a.61.61 0 0 1-.082.054 1.678 1.678 0 0 1-.677.175Z" fill="#fff" stroke="#316474"></path><path d="m283.734 125.679-138.87 9.684c-2.87.2-5.371-1.963-5.571-4.823l-6.234-88.905c-.201-2.86 1.972-5.35 4.844-5.55l138.87-9.684c2.874-.2 5.371 1.963 5.572 4.823l6.233 88.905c.201 2.86-1.971 5.349-4.844 5.55Z" fill="#fff"></path><path d="M144.864 135.363c-2.87.2-5.371-1.963-5.571-4.823l-6.234-88.905c-.201-2.86 1.972-5.35 4.844-5.55l138.87-9.684c2.874-.2 5.371 1.963 5.572 4.823l6.233 88.905c.201 2.86-1.971 5.349-4.844 5.55" stroke="#316474"></path><path d="m278.565 121.405-129.885 9.058c-2.424.169-4.506-1.602-4.668-3.913l-5.669-80.855c-.162-2.31 1.651-4.354 4.076-4.523l129.885-9.058c2.427-.169 4.506 1.603 4.668 3.913l5.669 80.855c.162 2.311-1.649 4.354-4.076 4.523Z" fill="#EEFEFA" stroke="#316474"></path><path d="m230.198 129.97 68.493-4.777.42 5.996c.055.781-.098 1.478-.363 1.972-.27.5-.611.726-.923.748l-165.031 11.509c-.312.022-.681-.155-1.017-.613-.332-.452-.581-1.121-.636-1.902l-.42-5.996 68.494-4.776c.261.79.652 1.483 1.142 1.998.572.6 1.308.986 2.125.929l24.889-1.736c.817-.057 1.491-.54 1.974-1.214.413-.577.705-1.318.853-2.138Z" fill="#42CBA5" stroke="#316474"></path><path d="m230.367 129.051 69.908-4.876.258 3.676a1.51 1.51 0 0 1-1.403 1.61l-168.272 11.735a1.51 1.51 0 0 1-1.613-1.399l-.258-3.676 69.909-4.876a3.323 3.323 0 0 0 3.188 1.806l25.378-1.77a3.32 3.32 0 0 0 2.905-2.23Z" fill="#fff" stroke="#316474"></path><circle transform="rotate(-3.989 1304.861 -2982.552) skewX(.021)" fill="#42CBA5" stroke="#316474" r="15.997"></circle><path d="m208.184 87.11-3.407-2.75-.001-.002a1.952 1.952 0 0 0-2.715.25 1.89 1.89 0 0 0 .249 2.692l.002.001 5.077 4.11v.001a1.95 1.95 0 0 0 2.853-.433l8.041-12.209a1.892 1.892 0 0 0-.573-2.643 1.95 1.95 0 0 0-2.667.567l-6.859 10.415Z" fill="#fff" stroke="#316474"></path></svg></span></div>
                        <h1 class="text-center text-gray-500 text-2xl mt-4">WhatsApp para escritorio</h1>

                    </div>

                </div>
                
            @endif
        </div>

    </div>


    @push('js')
        <script>

            function data(){
                return{

                    chat_id: @entangle('chat_id'),
                    typingChatId: null,

                    init(){
                      
                        Echo.private('App.Models.User.' + {{ auth()->id() }})
                            .notification((notification) => {

                                if (notification.type == 'App\\Notifications\\UserTyping') {
                                    this.typingChatId = notification.chat_id;

                                    setTimeout(() => {
                                        this.typingChatId = null;
                                    }, 3000);

                                }
                                
                            });

                    }

                }    
            }

            Livewire.on('scrollIntoView', function() {
                document.getElementById('final').scrollIntoView(true);
            });
            
        </script>
    @endpush

</div>

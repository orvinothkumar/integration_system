<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    @if (session()->has('message'))
        <div x-data="{ show: false }" x-init="() => {
            setTimeout(() => show = true, 500);
            setTimeout(() => show = false, 15000);
        }" x-show="show"
            class="fixed inset-x-0 top-10 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
            <div x-description="Notification panel, show/hide based on alert state." @click.away="show = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90"
                class="bg-green-100 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto">
                <div class="rounded-lg shadow-xs overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                {{-- <x-svg.icons.check-circle class="h-6 w-6 text-green-400"/> --}}
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm leading-5 font-medium text-gray-900">
                                    {{ session('message') }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button @click="show = false"
                                    class="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150">
                                    {{-- <x-svg.icons.x class="h-5 w-5" /> --}}
                                    <svg class="fill-current h-6 w-6 text-gray-700" role="button"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <title>Close</title>
                                        <path
                                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                    </svg>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="mt-8 text-2xl flex justify-between">
        <div class="page-head">
            Logs
        </div>
        <div>
            <x-jet-button wire:click="confirmLogAdd" class="bg-blue-500 hover:bg-blue-700">
                {{ __('Add New') }}
            </x-jet-button>
        </div>
    </div>
    {{-- {{$query}} --}}

    <div class="mt-6">
        <div class="flex justify-between">
            <div class="search-field">
                <input autocomplete="off" wire:model.debounce.500ms="q" type="search" placeholder="Search"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
        </div>
        @if ($logs->count())
            <div class="flex flex-col mt-4">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Device ID
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ward Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Room Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            SPO2
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pulse
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Respiration
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" x-max="1">
                                    @foreach ($logs as $log)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->device_uuid }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $log->ward_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $log->room_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->spo2 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->pulse }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->respiration }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-center">
                                                    <div wire:click="confirmLogEdit({{ $log->id }})"
                                                        class="flex items-center justify-center cursor-pointer">
                                                        <svg class="fill-current h-4 w-4 text-indigo-600 hover:text-indigo-900"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path
                                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                            <path fill-rule="evenodd"
                                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        <a class="ml-1 text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    </div>
                                                    @if (auth()->user()->role_id == 1)
                                                        <div wire:click="confirmLogDeletion({{ $log->id }})"
                                                            class="flex items-center justify-center cursor-pointer ml-4">
                                                            <svg class="fill-current h-4 w-4 text-red-600 hover:text-red-900"
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            <a class="ml-1 text-red-600 hover:text-red-900">Delete</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mt-4 bg-white overflow-hidden">
                <div class="header-container container flex justify-between p-10">
                    <p class="w-full text-center">No Data Found</p>
                </div>
            </div>
        @endif
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>

    <!-- Delete Log Confirmation Modal -->
    <x-jet-dialog-modal wire:model="confirmingLogDeletion">
        <x-slot name="title">
            {{ __('Delete Log') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete log?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('confirmingLogDeletion', false)">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deleteLog({{ $confirmingLogDeletion }})">
                {{ __('Delete') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Add Log Modal -->
    <x-jet-dialog-modal wire:model="confirmingLogAdd">
        <x-slot name="title">
            <div class="font-bold">{{ isset($this->log->id) ? 'Edit Log' : 'Add Log' }}
            </div>
        </x-slot>

        <form wire:submit.prevent="saveLog">
            <x-slot name="content">
                <!-- Device ID -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <x-jet-label class="font-semibold" for="device_uuid" value="{{ __('Device ID') }}" />
                    <x-jet-input id="device_uuid" type="text" class="mt-1 block w-full"
                        wire:model.defer="log.device_uuid" autocomplete="off" />
                    <x-jet-input-error for="log.device_uuid" class="mt-2" />
                </div>
                <!-- Ward Name -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <x-jet-label for="ward_name" value="{{ __('Ward Name') }}" />
                    <x-jet-input id="ward_name" type="text" class="mt-1 block w-full" wire:model.defer="log.ward_name"
                        autocomplete="off" />
                    <x-jet-input-error for="log.ward_name" class="mt-2" />
                </div>
                <!-- Room Name -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <x-jet-label for="room_name" value="{{ __('Room Name') }}" />
                    <x-jet-input id="room_name" type="text" class="mt-1 block w-full" wire:model.defer="log.room_name"
                        autocomplete="off" />
                    <x-jet-input-error for="log.room_name" class="mt-2" />
                </div>
                <!-- SPO2 -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <x-jet-label for="spo2" value="{{ __('SPO2') }}" />
                    <x-jet-input id="spo2" type="text" class="mt-1 block w-full" wire:model.defer="log.spo2"
                        autocomplete="off" />
                    <x-jet-input-error for="log.spo2" class="mt-2" />
                </div>
                <!-- Pulse -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <x-jet-label for="pulse" value="{{ __('Pulse') }}" />
                    <x-jet-input id="pulse" type="text" class="mt-1 block w-full" wire:model.defer="log.pulse"
                        autocomplete="off" />
                    <x-jet-input-error for="log.pulse" class="mt-2" />
                </div>
                <!-- Respiration -->
                <div class="col-span-6 sm:col-span-4 mt-4">
                    <x-jet-label class="font-semibold" for="respiration" value="{{ __('Respiration') }}" />
                    <x-jet-input id="respiration" type="text" class="mt-1 block w-full"
                        wire:model.defer="log.respiration" autocomplete="off" />
                    <x-jet-input-error for="log.respiration" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <div wire:loading wire:target="saveLog" class="text-sm text-gray-500 italic">Saving Data...
                </div>
                <x-jet-secondary-button wire:click="$set('confirmingLogAdd', false)">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button wire:loading.class="opacity-50 cursor-not-allowed" type="submit"
                    class="ml-2" wire:click="saveLog()">
                    {{ __('Save') }}
                </x-jet-danger-button>
            </x-slot>
        </form>
    </x-jet-dialog-modal>
</div>

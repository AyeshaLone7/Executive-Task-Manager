<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Task Manager</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js for Modal functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            background-color: #090a0f;
            color: #e2e8f0;
        }
        .luxury-card {
            background: linear-gradient(135deg, #12151e 0%, #0d0f17 100%);
            border: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .gold-text {
            background: linear-gradient(to right, #bf953f, #fcf6ba, #b38728, #fbf5b7, #aa771c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .gold-border:focus {
            border-color: #d4af37;
            ring-color: #d4af37;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen py-10 px-4 sm:px-6 lg:px-8" x-data="{ editModal: false, activeTask: {} }">

    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold tracking-wider gold-text uppercase">Executive Task Manager</h1>
            <p class="text-sm text-gray-400 mt-2">Manage your elite objectives with precision</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-950/80 border border-emerald-800/60 text-emerald-400 text-sm shadow-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Add Task Form Section -->
            <div class="md:col-span-1 luxury-card p-6 rounded-2xl h-fit">
                <h2 class="text-xl font-semibold text-gray-200 mb-4 border-b border-gray-800 pb-2">New Task</h2>
                <form action="{{ route('tasks.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Title</label>
                        <input type="text" name="title" required class="w-full bg-[#161a25] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none gold-border" placeholder="Enter title...">
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Description</label>
                        <textarea name="description" rows="3" required class="w-full bg-[#161a25] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none gold-border" placeholder="Enter description..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Priority</label>
                        <select name="priority" class="w-full bg-[#161a25] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none gold-border">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High (Urgent)</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-lg bg-gradient-to-r from-amber-600 to-yellow-600 text-gray-950 font-bold text-sm tracking-wide hover:from-amber-500 hover:to-yellow-500 transition shadow-lg">
                        Create Task
                    </button>
                </form>
            </div>

            <!-- Task List View Section -->
            <div class="md:col-span-2 space-y-4">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Active Tasks</h2>
                @forelse($tasks as $task)
                    <div class="luxury-card p-5 rounded-xl flex justify-between items-start transition hover:border-amber-500/40">
                        <div class="space-y-2 pr-4">
                            <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                <h3 class="font-bold text-gray-100 text-lg">{{ $task->title }}</h3>
                                <!-- Priority Badge -->
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-medium 
                                    {{ $task->priority == 'High' ? 'bg-red-950 text-red-400 border border-red-900' : ($task->priority == 'Medium' ? 'bg-amber-950 text-amber-400 border border-amber-900' : 'bg-blue-950 text-blue-400 border border-blue-900') }}">
                                    {{ $task->priority }}
                                </span>
                                <!-- Status Badge -->
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-medium 
                                    {{ $task->status == 'Completed' ? 'bg-emerald-950 text-emerald-400 border border-emerald-900' : 'bg-yellow-950 text-yellow-400 border border-yellow-900' }}">
                                    {{ $task->status ?? 'Pending' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-400 whitespace-pre-line">{!! nl2br(e($task->description)) !!}</p>
                            <span class="text-[10px] text-gray-500 block pt-1">{{ $task->created_at->format('d M Y, h:i A') }}</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center space-x-1">
                            <!-- Edit Button -->
                            <button @click="editModal = true; activeTask = {{ json_encode($task) }}" class="text-gray-500 hover:text-amber-400 p-2 transition" title="Edit Task">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-500 hover:text-red-400 p-2 transition" title="Delete Task">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2.015 2.015 0 0116.138 21H7.862a2.015 2.015 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="luxury-card p-10 rounded-xl text-center text-gray-500">
                        No tasks found. Create your first luxury task on the left.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Edit Task Modal Popup -->
    <div x-show="editModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 z-50" x-cloak>
        <div class="luxury-card p-6 rounded-2xl w-full max-w-md relative" @click.away="editModal = false">
            <h2 class="text-xl font-semibold text-gray-200 mb-4 border-b border-gray-800 pb-2">Edit Task</h2>
            
            <form :action="'/tasks/' + activeTask.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Title</label>
                    <input type="text" name="title" x-model="activeTask.title" required class="w-full bg-[#161a25] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none gold-border">
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Description</label>
                    <textarea name="description" rows="3" x-model="activeTask.description" required class="w-full bg-[#161a25] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none gold-border"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Priority</label>
                        <select name="priority" x-model="activeTask.priority" class="w-full bg-[#161a25] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none gold-border">
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High (Urgent)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider text-gray-400 mb-1">Status</label>
                        <select name="status" x-model="activeTask.status" class="w-full bg-[#161a25] border border-gray-800 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none gold-border">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" @click="editModal = false" class="px-4 py-2 rounded-lg bg-gray-800 text-gray-300 text-sm hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-gradient-to-r from-amber-600 to-yellow-600 text-gray-950 font-bold text-sm hover:from-amber-500 hover:to-yellow-500 transition shadow-lg">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
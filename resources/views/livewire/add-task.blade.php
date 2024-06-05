<div style="background-color: #2c3e50; padding: 20px; border-radius: 10px; color: white; width: 500px; margin: auto;">
<button id="hideButton" style="height: 20px; width: 20px; float: right; margin-top: 0; padding: 0;" onclick="toggleTaskComponent()"><i class="fa-solid fa-circle-xmark" style="color: #ff0000;"></i></button>
    <form method="POST" action="/addNewTask">
        @csrf
        <div class="mt-4">

            <label for="name" style="display: block; margin-bottom: 10px; font-weight: bold;">Taaknaam</label>
            <input id="name" name="name" class="block mt-1 w-full" type="text" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
            
            <label for="description" style="display: block; margin-bottom: 10px; font-weight: bold;">Beschrijving</label>
            <textarea id="description" name="description" class="block mt-1 w-full" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; height: 100px; margin-bottom: 20px;"></textarea>
            
            <label for="deadline" style="display: block; margin-bottom: 10px; font-weight: bold;">Deadline</label>
            <input id="deadline" name="deadline" class="block mt-1 w-full" type="date" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
            
            <label for="course" style="display: block; margin-bottom: 10px; font-weight: bold;">Vak</label>
            <select id="course" name="course" class="block mt-1 w-full" wire:model="course" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
                @foreach ($courses as $course)
                    <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                @endforeach
            </select>

            <label for="taskType" style="display: block; margin-bottom: 10px; font-weight: bold;">Type Taak</label>
            <select id="taskType" name="taskType" class="block mt-1 w-full" wire:model="taskType" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
                @foreach ($taskTypes as $taskType)
                    <option value="{{ $taskType['id'] }}">{{ $taskType['name'] }}</option>
                @endforeach
            </select>
            
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="completed" value="0">
            <input type="hidden" name="created_at" value="{{ date('Y-m-d H:i:s') }}">
            <input type="hidden" name="createdBy" value="3">
            
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; border-radius: 5px; background-color: #2980b9; border: none; font-size: 16px; font-weight: bold; color: white;">Taak toevoegen</button>
        </div>
    </form>
</div>

@props(['project', 'allUsers'])

<div class="card glass-card">
    <div class="card-header">
        <h5>Assign Users</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('projects.users.assign', $project) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="users" class="form-label">Select Users</label>
                <select id="users" name="users[]" class="form-select" multiple required>
                    @foreach ($allUsers as $user)
                        <option value="{{ $user->id }}" {{ $project->users->contains($user->id) ? 'disabled' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Selected</button>
        </form>
    </div>
</div>

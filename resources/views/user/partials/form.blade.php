<div class="card-body">
    <div class="form-group">
        <label for="firstname">Firstname</label>
        <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname" value="{{ isset($user) ? $user->firstname : old('firstname') }}">
    </div>
    <div class="form-group">
        <label for="lastname">Lastname</label>
        <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Lastname" value="{{ isset($user) ? $user->lastname : old('lastname') }}">
    </div>
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="text"
               class="form-control disabled"
               name="email"
               id="email"
               placeholder="Email Address"
               autocomplete="off"
               value="{{ isset($user) ? $user->email : '' }}"
               {{ isset($user) ? "readonly" : "" }}>
    </div>
</div>

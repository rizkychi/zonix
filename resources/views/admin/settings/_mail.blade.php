<p class="text-muted mb-2">
    {{ __('Configure your site\'s mail settings, including mail driver, host, port, and more.') }}
</p>

<div class="row g-3">
    <div class="col-md-6">
        <x-input name="driver" :label="__('Driver')" :value="$settings->driver" required />
    </div>
    <div class="col-md-6">
        <x-input name="encryption" :label="__('Encryption')" :value="$settings->encryption" />
    </div>
    <div class="col-md-6">
        <x-input name="host" :label="__('Host')" :value="$settings->host" required />
    </div>
    <div class="col-md-6">
        <x-input name="port" :label="__('Port')" :value="$settings->port" required type="number" />
    </div>
    <div class="col-md-6">
        <x-input name="username" :label="__('Username')" :value="$settings->username" />
    </div>
    <div class="col-md-6">
        <x-input name="password" :label="__('Password')" :value="$settings->password" type="password" />
    </div>
    <div class="col-md-6">
        <x-input name="from_address" :label="__('From Address')" :value="$settings->from_address" required type="email" />
    </div>
    <div class="col-md-6">
        <x-input name="from_name" :label="__('From Name')" :value="$settings->from_name" required />
    </div>
</div>

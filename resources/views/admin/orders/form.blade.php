
<div class="form-group">
        <label for="code">Code</label>
        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="type">Type</label>
        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
            <option value="">Select type</option>
            <option value="value" {{ old('type') == 'value' ? 'selected' : '' }}>Value</option>
            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
        </select>
        @error('type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount') }}" required>
        @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label for="expires_at">Expires At</label>
        <input type="date" class="form-control @error('expires_at') is-invalid @enderror" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" required>
        @error('expires_at')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
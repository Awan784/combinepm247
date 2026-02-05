<form action="{{ route('uploadCSV') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file">Choose CSV file:</label>
    <input type="file" name="file" id="file" required>
    <button type="submit">Upload</button>
</form>

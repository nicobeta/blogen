<html>
    @json($errors)
    <form method="POST" enctype="multipart/form-data" action="/api/posts/1?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9ibG9nLmRldi9hcGkvYXV0aC9zaWdudXAiLCJpYXQiOjE1MDYwMTkwMDYsImV4cCI6MTUwNjAyMjYwNiwibmJmIjoxNTA2MDE5MDA2LCJqdGkiOiJCZ1JaYno1dGo0cFFxR3F0In0.q6lise1tdZoTkKja7dt5JobGjEQwPRbQgdFkn7lRhyE">
        <input name="_method" type="hidden" value="PUT">
        <input name="title" type="text" value="MAn">
		
        <input type="file" name="image">
        <button>
            Submit
        </button>
    </form>
</html>

 
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">students</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="{{route('addStudent')}}">ADD</a></li>
      <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href=""{{route('students')}}"">Page 1 <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="{{route('students')}}">Students</a></li>
          <li><a href="{{route('trashStudent')}}">trash</a></li>
          <li><a href="#">Page 1-3</a></li>
        </ul>
      </li>
      <li><a href="#">Page 2</a></li>
      <li><a href="#">Page 3</a></li>
    </ul>
  </div>
</nav>
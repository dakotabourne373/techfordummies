<?php
//Dakota Bourne and Matthew Reid
    $authtenticated = isset($_SESSION["username"]);
    $html = $authtenticated ? 
    "<nav class='navbar fixed-top navbar-expand-xl navbar-light bg-light'>
        <a class='navbar-brand uvaTitle'
        href='{$this->url}index' style='color: #211f31'>TechForDummies</a><button class='navbar-toggler'
        type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent'
        aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'><span
            class='navbar-toggler-icon'></span></button>
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
        <ul class='navbar-nav ml-auto'>
            <li class='nav-item'><a class='nav-link' href='{$this->url}posts/'>Posts<span class='sr-only'></span></a>
            </li>
            <div class='separate-nav-item'></div>
            <li class='nav-item'><a class='nav-link' href='{$this->url}index/'>About<span class='sr-only'></span></a></li>
            <div class='separate-nav-item'></div>
            <li class='nav-item'><a class='nav-link' href='{$this->url}profile/'
                >My Profile<span class='sr-only'></span></a></li>
            <div class='separate-nav-item'></div>
            <li class='nav-item' style='margin-right: 20px;'><a class='nav-link' href='{$this->url}logout/'>Logout</a></li>
        </ul>
    </div>
</nav>" : "<nav class='navbar fixed-top navbar-expand-sm navbar-light bg-light'><a class='navbar-brand uvaTitle'
href='{$this->url}index/' style='color: #232d4b'>TechForDummies</a><button class='navbar-toggler' type='button'
data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent'
aria-expanded='false' aria-label='Toggle navigation'><span class='navbar-toggler-icon'></span></button>
<div class='collapse navbar-collapse' id='navbarSupportedContent'>
<ul class='navbar-nav mr-auto'>
        <li class='nav-item'><a class='nav-link' href='{$this->url}posts/'>Posts<span class='sr-only'></span></a>
            </li>

            <div class='separate-nav-item'></div>

    <li class='nav-item active'><a class='nav-link' href='{$this->url}index/'>About<span class='sr-only'></span></a>
    </li>
    <div class='separate-nav-item'></div>
    <li class='nav-item' style='margin-right: 20px;'><a class='nav-link' href='{$this->url}login/'
            data-theme='dark'>Login</a></li>
</ul>
</div>
</nav>";
echo $html;
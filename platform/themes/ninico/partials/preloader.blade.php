@if(theme_option('preloader_version', 'v1') === 'v1')
    <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div>
@else
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            background-color: rgba(255, 255, 255, 0.82);
        }

        #preloader .preloader-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: block;
        }

        #preloader .preloader-loading::after {
            content: " ";
            display: block;
            border-radius: 50%;
            border-width: 1px;
            border-style: solid;
            -webkit-animation: lds-dual-ring 0.5s linear infinite;
            animation: lds-dual-ring 0.5s linear infinite;
            width: 40px;
            height: 40px;
            border-color: var(--primary-color) transparent var(--primary-color) transparent;
        }
    </style>
    <div id="preloader">
        <div class="preloader-loading"></div>
    </div>
@endif

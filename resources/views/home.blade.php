<x-app-layout>
    <x-slot name="header">
        <div style="text-align: center;">
            <h1 style="color: purple;">Kapsalon</h1>
            <h3 style="color: cobalt;">Maak nu een afspraak!</h3>
            <button style="background-color: cobalt;">
                <span style="color: white;">Afspraak maken</span>
            </button>
        </div>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col">
                <a href="{{ route('products') }}">
                    <div class="image-container" style="background-color: purple;">
                        <img src="{{ asset('/assets/img/placeholder.jpg') }}" alt="Product Image">
                    </div>
                </a>
                <button style="background-color: purple;">
                    <span style="color: white;">Shop</span>
                </button>
            </div>
            <div class="col">
                <a href="{{ route('appointment') }}">
                    <div class="image-container" style="background-color: purple;">
                        <img src="{{ asset('/assets/img/placeholder.jpg') }}" alt="Appointment Image">
                    </div>
                </a>
                <button style="background-color: purple;">
                    <span style="color: white;">Afspraak maken</span>
                </button>
            </div>
            <div class="col">
                <a href="{{ route('contact') }}">
                    <div class="image-container" style="background-color: purple;">
                        <img src="{{ asset('/assets/img/placeholder.jpg') }}" alt="Contact Image">
                    </div>
                </a>
                <button style="background-color: purple;">
                    <span style="color: white;">Contact</span>
                </button>
            </div>
        </div>
    </div>
    
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px 0;
        }
        
        .col {
            flex: 0 0 300px;
            margin: 10px;
            text-align: center;
        }
        
        .image-container {
            background-size: cover;
            height: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
        
        button {
            margin-top: 10px;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        button:hover {
            opacity: 0.8;
        }

        button span {
            font-size: 16px;
        }
        
        @media (max-width: 768px) {
            .col {
                flex: 0 0 100%;
            }
        }
    </style>
</x-app-layout>

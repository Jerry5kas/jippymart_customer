@extends('customer.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 fw-bold text-center mb-5">About JippyMart</h1>
            
            <div class="row mb-5">
                <div class="col-md-6">
                    <img src="{{ asset('images/about-us.jpg') }}" alt="About JippyMart" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <h2>Our Story</h2>
                    <p class="lead">JippyMart was founded with a simple mission: to make your life easier by providing fast, reliable delivery of groceries, medicines, and daily essentials.</p>
                    <p>We understand that in today's fast-paced world, convenience is key. That's why we've built a comprehensive platform that brings everything you need right to your doorstep.</p>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center mb-4">Our Mission</h2>
                    <p class="text-center lead">To provide the freshest products, fastest delivery, and best customer service while making online shopping simple and enjoyable for everyone.</p>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-primary"></i>
                    </div>
                    <h4>Customer First</h4>
                    <p>We put our customers at the center of everything we do, ensuring their satisfaction and happiness.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-star fa-3x text-warning"></i>
                    </div>
                    <h4>Quality Assured</h4>
                    <p>We maintain the highest standards of quality in all our products and services.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-heart fa-3x text-danger"></i>
                    </div>
                    <h4>Community Focused</h4>
                    <p>We're committed to supporting our local community and building lasting relationships.</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mb-4">Why Choose Us?</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Fast 30-minute delivery</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Fresh, quality products</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Competitive prices</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>24/7 customer support</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Secure payment options</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Easy returns & refunds</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Wide product selection</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Regular offers & discounts</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ url('/contact') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-envelope me-2"></i>Get in Touch
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - ExDrive</title>

    <meta name="description" content="ExDrive Ride Dispatch Management System Terms & Conditions">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-slate-900 antialiased">

    <div x-data="{ pricing: false }">

        @include('partials.landing-header')

        {{-- Header --}}
        <section class="bg-slate-50 border-b border-slate-100">
            <div class="max-w-5xl mx-auto px-6 lg:px-8 py-20">

                <div
                    class="inline-flex items-center rounded-full bg-white px-4 py-1.5 text-[11px] font-black uppercase tracking-[0.3em] text-sky-600 shadow-sm border border-slate-100">
                    Legal
                </div>

                <h1 class="mt-6 text-4xl sm:text-5xl font-black tracking-tight text-slate-900">
                    Terms & Conditions
                </h1>

                <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                    ExDrive – Ride Dispatch Management System
                </p>

                <div class="mt-2 text-sm text-slate-400 font-semibold">
                    Provided by Extech Studio
                </div>

                <div class="text-sm text-slate-400 font-semibold">
                    Last Updated: March 2026
                </div>

            </div>
        </section>

        {{-- Content --}}
        <section class="py-20">
            <div class="max-w-5xl mx-auto px-6 lg:px-8">

                <div class="bg-white border border-slate-200 rounded-[2rem] p-10 lg:p-14 shadow-sm space-y-10">

                    {{-- 1 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">1. Introduction</h2>

                        <p class="mt-4 text-slate-600 leading-relaxed">
                            These Terms & Conditions govern the use of the ExDrive Ride Dispatch Management System
                            ("System", "Software", or "Platform") provided by Extech Studio.
                        </p>

                        <p class="mt-4 text-slate-600 leading-relaxed">
                            By accessing, registering, purchasing, or using this system, you agree to be bound by these
                            Terms & Conditions.
                        </p>

                        <p class="mt-4 text-slate-600 leading-relaxed">
                            ExDrive is a software platform designed to assist businesses in managing ride bookings,
                            dispatching drivers, and organizing transportation operations.
                        </p>
                    </div>

                    {{-- 2 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">2. Description of Service</h2>

                        <p class="mt-4 text-slate-600">ExDrive provides a digital platform that may include features
                            such as:</p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Ride booking management</li>
                            <li>• Driver dispatch system</li>
                            <li>• Driver and customer management</li>
                            <li>• Order and trip tracking</li>
                            <li>• Fleet and shift management</li>
                            <li>• Operational dashboard and reports</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            The system is provided as a software management tool only.
                        </p>

                        <p class="mt-2 text-slate-600">
                            Extech Studio does not operate transportation services, provide drivers, or manage vehicles.
                        </p>
                    </div>

                    {{-- 3 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">3. Software License</h2>

                        <p class="mt-4 text-slate-600">
                            Upon purchase or subscription, the client is granted a limited, non-transferable license to
                            use the ExDrive system.
                        </p>

                        <p class="mt-4 text-slate-600">Clients are not allowed to:</p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Resell the software without written permission</li>
                            <li>• Copy, distribute, or reproduce the source code</li>
                            <li>• Modify or reverse engineer the system</li>
                            <li>• Use the software for illegal activities</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            All intellectual property rights remain the property of Extech Studio.
                        </p>
                    </div>

                    {{-- 4 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">4. Client Responsibilities</h2>

                        <p class="mt-4 text-slate-600">
                            Clients are responsible for managing their own transportation operations, including:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Managing drivers and employees</li>
                            <li>• Handling customer bookings</li>
                            <li>• Managing payments between customers and drivers</li>
                            <li>• Ensuring compliance with local laws and regulations</li>
                        </ul>

                        <p class="mt-4 text-slate-600">Extech Studio is not responsible for disputes between:</p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Drivers and customers</li>
                            <li>• Companies and drivers</li>
                            <li>• Transportation service incidents</li>
                        </ul>
                    </div>

                    {{-- 5 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">5. System Availability</h2>

                        <p class="mt-4 text-slate-600">
                            Extech Studio aims to maintain reliable system availability.
                        </p>

                        <p class="mt-4 text-slate-600">
                            However, service interruptions may occur due to:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Server maintenance</li>
                            <li>• System upgrades</li>
                            <li>• Hosting provider issues</li>
                            <li>• Network problems</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            We do not guarantee that the system will operate without interruption at all times.
                        </p>
                    </div>

                    {{-- 6 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">6. Technical Support</h2>

                        <p class="mt-4 text-slate-600">
                            Technical support may be provided depending on the service plan.
                        </p>

                        <p class="mt-4 text-slate-600">
                            Support services may include:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Bug fixes</li>
                            <li>• System troubleshooting</li>
                            <li>• Basic technical assistance</li>
                        </ul>

                        <p class="mt-4 text-slate-600">Support does not include:</p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Custom feature development</li>
                            <li>• Third-party integrations not included in the system</li>
                            <li>• Custom modifications</li>
                        </ul>
                    </div>

                    {{-- 7 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">7. Maintenance & Updates</h2>

                        <p class="mt-4 text-slate-600">
                            Extech Studio may release updates to improve the system, including:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Bug fixes</li>
                            <li>• Security updates</li>
                            <li>• Feature improvements</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            Some updates may temporarily affect system availability.
                        </p>
                    </div>

                    {{-- 8 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">8. Payment Terms</h2>

                        <p class="mt-4 text-slate-600">
                            Clients may be required to pay:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• One-time license fees</li>
                            <li>• Subscription fees</li>
                            <li>• Maintenance fees (if applicable)</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            Failure to complete payment may result in:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Suspension of system access</li>
                            <li>• Service limitations</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            Payments are non-refundable unless otherwise stated.
                        </p>
                    </div>

                    {{-- 9 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">9. Illegal Use</h2>

                        <p class="mt-4 text-slate-600">
                            Clients may not use the system for illegal activities including but not limited to:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Operating illegal transportation services</li>
                            <li>• Fraudulent activities</li>
                            <li>• Misuse of customer data</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            Extech Studio reserves the right to suspend or terminate accounts that violate these terms.
                        </p>
                    </div>

                    {{-- 10 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">10. Limitation of Liability</h2>

                        <p class="mt-4 text-slate-600">
                            Extech Studio shall not be liable for any:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Business losses</li>
                            <li>• Operational losses</li>
                            <li>• Transportation incidents or accidents</li>
                            <li>• Driver misconduct</li>
                            <li>• Disputes between drivers and customers</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            The ExDrive system is a software tool only and does not control transportation operations.
                        </p>
                    </div>

                    {{-- 11 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">11. Data Responsibility</h2>

                        <p class="mt-4 text-slate-600">
                            Clients are responsible for all data entered into the system, including:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Customer information</li>
                            <li>• Driver information</li>
                            <li>• Booking records</li>
                        </ul>

                        <p class="mt-4 text-slate-600">
                            While Extech Studio implements reasonable security measures, we cannot guarantee absolute
                            data security.
                        </p>
                    </div>

                    {{-- 12 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">12. Termination</h2>

                        <p class="mt-4 text-slate-600">
                            Extech Studio reserves the right to suspend or terminate system access if:
                        </p>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>• Terms & Conditions are violated</li>
                            <li>• Fraudulent or illegal activities are detected</li>
                            <li>• Payment obligations are not fulfilled</li>
                        </ul>
                    </div>

                    {{-- 13 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">13. Changes to Terms</h2>

                        <p class="mt-4 text-slate-600">
                            Extech Studio may update these Terms & Conditions at any time.
                        </p>

                        <p class="mt-4 text-slate-600">
                            Updated terms will take effect once published on the website.
                        </p>
                    </div>

                    {{-- 14 --}}
                    <div>
                        <h2 class="text-xl font-black text-slate-900">14. Contact Information</h2>

                        <ul class="mt-4 space-y-2 text-slate-600">
                            <li>Company: Extech Studio</li>
                            <li>Email: cs.extechstudio@gmail.com</li>
                            <li>WhatsApp: 011-5689 8898</li>
                        </ul>
                    </div>

                </div>
            </div>
        </section>

        @include('partials.landing-footer')

    </div>
</body>

</html>

<?php
// index.php - Companio AI Landing Page
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companio AI | Your AI Caregiver Companion</title>
    <meta name="description" content="Stateful AI that remembers conversations, reminds medicines, and supports elderly people while keeping caregivers informed.">
    
    <!-- Tailwind CSS Output -->
    <link href="assets/css/output.css" rel="stylesheet">
    
    <!-- Modern Typography: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; }
        
        /* Animated Gradient for Hero Text */
        .hero-gradient {
            background: linear-gradient(-45deg, #4f46e5, #0ea5e9, #8b5cf6, #ec4899);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Glassmorphism Styles */
        .glass-card {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased selection:bg-indigo-500 selection:text-white overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-card border-b-0 border-b-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <i data-lucide="brain-circuit" class="text-white w-6 h-6"></i>
                    </div>
                    <span class="font-bold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-purple-400">Companio AI</span>
                </div>
                
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-8">
                        <a href="#features" class="hover:text-white text-slate-400 transition-colors px-3 py-2 text-sm font-medium">Features</a>
                        <a href="#how-it-works" class="hover:text-white text-slate-400 transition-colors px-3 py-2 text-sm font-medium">How it Works</a>
                        <a href="#about" class="hover:text-white text-slate-400 transition-colors px-3 py-2 text-sm font-medium">About</a>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center gap-4">
                    <a href="auth/login.php" class="text-slate-300 hover:text-white px-4 py-2 text-sm font-medium transition-colors">Login</a>
                    <a href="auth/register.php" class="bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 hover:text-indigo-200 px-6 py-2.5 rounded-full text-sm font-semibold transition-all hover:scale-105 shadow-[0_0_15px_rgba(99,102,241,0.1)]">Get Started</a>
                </div>
                
                <div class="md:hidden flex items-center">
                    <button class="text-slate-300 hover:text-white focus:outline-none">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- 1. Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 min-h-screen flex items-center">
        <!-- Ambient Glowing Orbs -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-[120px] mix-blend-screen -z-10 animate-pulse pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[100px] mix-blend-screen -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px] mix-blend-screen -z-10 pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-card mb-8 border border-white/5">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-xs font-semibold tracking-wide uppercase text-slate-300">Empowering Caregivers & Seniors</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold tracking-tight mb-8 leading-tight">
                Your AI <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text hero-gradient">Caregiver Companion</span>
            </h1>
            
            <p class="mt-4 max-w-3xl text-lg md:text-xl lg:text-2xl text-slate-400 mx-auto font-light leading-relaxed mb-12">
                Stateful AI that <span class="text-white font-medium">remembers conversations</span>, <span class="text-white font-medium">reminds medicines</span>, and supports elderly people while keeping caregivers informed in real-time.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center items-center">
                <a href="auth/register.php" class="w-full sm:w-auto relative group px-8 py-4 rounded-full bg-white text-slate-900 font-bold overflow-hidden transition-all hover:scale-105 shadow-[0_0_40px_rgba(255,255,255,0.2)] hover:shadow-[0_0_60px_rgba(255,255,255,0.4)]">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Get Started <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-100 to-slate-300 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left"></div>
                </a>
                
                <a href="auth/login.php" class="w-full sm:w-auto px-8 py-4 rounded-full glass-card hover:bg-white/10 border border-slate-700 text-white font-medium transition-all flex items-center justify-center gap-2 hover:border-slate-500">
                    Login to Dashboard <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                </a>
            </div>
            
            <!-- Dashboard preview mockup -->
            <div class="mt-24 relative max-w-5xl mx-auto hidden md:block">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent z-20 h-full w-full"></div>
                <div class="rounded-2xl border border-white/5 glass-card p-2 overflow-hidden transform perspective-1000 rotate-x-12 scale-95 opacity-80 shadow-[0_0_50px_rgba(79,70,229,0.15)]">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop" alt="Dashboard Preview" class="rounded-xl w-full object-cover filter grayscale contrast-125 saturate-50 opacity-50 mix-blend-luminosity">
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Features Section -->
    <section id="features" class="py-24 relative border-t border-white/5 bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20 lg:w-2/3 mx-auto">
                <h2 class="text-indigo-500 font-semibold tracking-wide uppercase text-sm mb-3">Core Capabilities</h2>
                <h3 class="text-3xl md:text-5xl font-bold text-white mb-6">Designed with Empathy,<br/>Powered by AI</h3>
                <p class="text-slate-400 text-lg">Every feature is meticulously crafted to bridge the gap between technical complexity and senior accessibility.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-6 border border-slate-700 group-hover:border-blue-500/50 transition-colors shadow-lg">
                        <i data-lucide="mic" class="w-6 h-6 text-blue-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3 text-slate-100">AI Voice Companion</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Natural, empathetic voice conversations reducing loneliness and providing constant companionship.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl group-hover:bg-purple-500/20 transition-all"></div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-6 border border-slate-700 group-hover:border-purple-500/50 transition-colors shadow-lg">
                        <i data-lucide="pill" class="w-6 h-6 text-purple-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3 text-slate-100">Medicine Reminder</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Timely, spoken alerts to take medication, with intelligent follow-ups to ensure adherence.</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-6 border border-slate-700 group-hover:border-emerald-500/50 transition-colors shadow-lg">
                        <i data-lucide="brain" class="w-6 h-6 text-emerald-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3 text-slate-100">Conversation Memory</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Stateful AI that remembers past interactions, stories, and preferences for a truly personalized experience.</p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-all"></div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-6 border border-slate-700 group-hover:border-rose-500/50 transition-colors shadow-lg">
                        <i data-lucide="siren" class="w-6 h-6 text-rose-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3 text-slate-100">Emergency SOS</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Voice-activated or single-touch emergency alerts that instantly notify caregivers and emergency services.</p>
                </div>

                <!-- Feature 5 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all"></div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-6 border border-slate-700 group-hover:border-amber-500/50 transition-colors shadow-lg">
                        <i data-lucide="pie-chart" class="w-6 h-6 text-amber-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3 text-slate-100">Caregiver Dashboard</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">Actionable analytics, health logs, and conversation summaries available remotely in real-time.</p>
                </div>

                <!-- Feature 6 -->
                <div class="glass-card p-8 rounded-3xl hover:-translate-y-2 transition-transform duration-300 group relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-cyan-500/10 rounded-full blur-2xl group-hover:bg-cyan-500/20 transition-all"></div>
                    <div class="w-14 h-14 rounded-2xl bg-slate-800/80 flex items-center justify-center mb-6 border border-slate-700 group-hover:border-cyan-500/50 transition-colors shadow-lg">
                        <i data-lucide="layout-template" class="w-6 h-6 text-cyan-400"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-3 text-slate-100">Dynamic UI</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">High-contrast, intuitive tablet & mobile interfaces adapting to cognitive or visual impairments.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. How It Works Section -->
    <section id="how-it-works" class="py-24 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6">How It Works</h2>
                <p class="text-slate-400 max-w-2xl mx-auto text-lg">A unified ecosystem seamless integrating family oversight with autonomous AI companionship.</p>
            </div>
            
            <div class="relative max-w-5xl mx-auto">
                <!-- Vertical Line for Mobile / Horizontal for Desktop -->
                <div class="hidden md:block absolute top-[40px] left-8 right-8 h-0.5 bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
                <div class="md:hidden absolute top-8 bottom-8 left-[39px] w-0.5 bg-gradient-to-b from-transparent via-indigo-500/50 to-transparent"></div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-y-12 md:gap-x-4 relative">
                    <!-- Step 1 -->
                    <div class="relative flex md:flex-col items-center md:text-center gap-6 md:gap-4 group z-10">
                        <div class="w-20 h-20 shrink-0 rounded-2xl glass-card flex items-center justify-center border border-indigo-500/30 group-hover:border-indigo-400 group-hover:-translate-y-1 transition-all shadow-[0_0_20px_rgba(99,102,241,0.1)] bg-slate-900">
                            <i data-lucide="user-plus" class="w-8 h-8 text-indigo-400"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-200 font-bold mb-1 text-lg">Register</h4>
                            <p class="text-sm text-slate-400">Caregiver creates an account and links elderly patient.</p>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative flex md:flex-col items-center md:text-center gap-6 md:gap-4 group z-10 md:translate-y-8">
                        <div class="w-20 h-20 shrink-0 rounded-2xl glass-card flex items-center justify-center border border-purple-500/30 group-hover:border-purple-400 group-hover:-translate-y-1 transition-all shadow-[0_0_20px_rgba(168,85,247,0.1)] bg-slate-900">
                            <i data-lucide="file-text" class="w-8 h-8 text-purple-400"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-200 font-bold mb-1 text-lg">History</h4>
                            <p class="text-sm text-slate-400">Store medical history and routine preferences.</p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative flex md:flex-col items-center md:text-center gap-6 md:gap-4 group z-10">
                        <div class="w-20 h-20 shrink-0 rounded-2xl glass-card flex items-center justify-center border border-pink-500/30 group-hover:border-pink-400 group-hover:-translate-y-1 transition-all shadow-[0_0_20px_rgba(236,72,153,0.1)] bg-slate-900">
                            <i data-lucide="message-square" class="w-8 h-8 text-pink-400"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-200 font-bold mb-1 text-lg">Interact</h4>
                            <p class="text-sm text-slate-400">AI converse & remembers daily interactions.</p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="relative flex md:flex-col items-center md:text-center gap-6 md:gap-4 group z-10 md:translate-y-8">
                        <div class="w-20 h-20 shrink-0 rounded-2xl glass-card flex items-center justify-center border border-emerald-500/30 group-hover:border-emerald-400 group-hover:-translate-y-1 transition-all shadow-[0_0_20px_rgba(16,185,129,0.1)] bg-slate-900">
                            <i data-lucide="bell-ring" class="w-8 h-8 text-emerald-400"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-200 font-bold mb-1 text-lg">Remind</h4>
                            <p class="text-sm text-slate-400">Automated, contextual medicine reminders trigger.</p>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="relative flex md:flex-col items-center md:text-center gap-6 md:gap-4 group z-10">
                        <div class="w-20 h-20 shrink-0 rounded-2xl glass-card flex items-center justify-center border border-blue-500/30 group-hover:border-blue-400 group-hover:-translate-y-1 transition-all shadow-[0_0_20px_rgba(59,130,246,0.1)] bg-slate-900">
                            <i data-lucide="bar-chart-2" class="w-8 h-8 text-blue-400"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-200 font-bold mb-1 text-lg">Summarize</h4>
                            <p class="text-sm text-slate-400">Caregiver views daily logs & actionable health summaries.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. About Section -->
    <section id="about" class="py-24 border-t border-white/5 relative bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2 w-full relative">
                    <!-- Tech Ring decorative -->
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] border border-white/5 rounded-full animate-[spin_40s_linear_infinite] z-0 pointer-events-none"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] h-[90%] border border-indigo-500/10 rounded-full animate-[spin_30s_linear_infinite_reverse] z-0 pointer-events-none"></div>
                    
                    <div class="glass-card p-2 rounded-3xl border border-slate-700 relative z-10 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1576765608535-5f04d1e3f289?q=80&w=1600&auto=format&fit=crop" alt="Connecting generations" class="rounded-[22px] w-full h-[450px] object-cover grayscale opacity-70 contrast-125 hover:grayscale-0 transition-all duration-700">
                        
                        <!-- Floating Notify Card -->
                        <div class="absolute -bottom-6 -right-6 glass-card p-5 rounded-2xl z-20 w-72 border border-slate-700 shadow-xl backdrop-blur-xl bg-slate-900/80">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 mt-1 rounded-full bg-emerald-500/20 flex flex-shrink-0 items-center justify-center border border-emerald-500/30">
                                    <i data-lucide="check" class="text-emerald-400 w-5 h-5"></i>
                                </div>
                                <div>
                                    <h5 class="text-white font-bold text-sm">Medication Logged</h5>
                                    <p class="text-xs text-slate-400 mt-1 line-clamp-2">"Dad just confirmed he took his morning Lisinopril."</p>
                                    <span class="text-[10px] text-indigo-400 mt-2 block font-medium">Just now</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="lg:w-1/2 space-y-8 relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-semibold tracking-wide uppercase">
                        <i data-lucide="heart" class="w-4 h-4"></i> The Vision
                    </div>
                    <h2 class="text-3xl md:text-5xl font-bold leading-tight text-white">Empowering Independence, <br/><span class="text-indigo-400">Ensuring Peace of Mind.</span></h2>
                    
                    <div class="space-y-5 text-slate-400 text-lg leading-relaxed">
                        <p>
                            Companio AI is built on the premise that age shouldn't mean isolation. By bridging empathetic artificial intelligence with robust medical tracking, we transform standard caregiving into an intuitive, autonomous experience.
                        </p>
                        <p>
                            Our stateful LLM framework ensures the AI doesn't just respond—it <span class="text-slate-200 font-medium">understands, retains context, and proactively cares</span>. It learns routines, engages in meaningful dialogue, and alerts families instantly when intervention is needed.
                        </p>
                    </div>
                    
                    <div class="pt-6 border-t border-white/10 flex items-center gap-6">
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold text-white">24<span class="text-indigo-500">/7</span></span>
                            <span class="text-xs text-slate-500 uppercase tracking-widest mt-1">Monitoring</span>
                        </div>
                        <div class="h-10 w-px bg-white/10"></div>
                        <div class="flex flex-col">
                            <span class="text-3xl font-bold text-white">100<span class="text-emerald-500">%</span></span>
                            <span class="text-xs text-slate-500 uppercase tracking-widest mt-1">Stateful Privacy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. Footer -->
    <footer class="glass-card mt-12 border-t border-slate-800 border-b-0 border-x-0 pt-16 pb-8 block relative overflow-hidden bg-slate-950/80">
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-3/4 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent shadow-[0_0_15px_3px_rgba(99,102,241,0.4)]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                <!-- Branding -->
                <div class="md:col-span-5">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i data-lucide="brain-circuit" class="text-white w-5 h-5"></i>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-white">Companio AI</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed max-w-sm mb-6">
                        Your smart, stateful caregiver supporting senior independence while delivering intelligent, real-time insights to families.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-indigo-500/20 border border-white/5 hover:border-indigo-500/30 transition-all"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:text-white hover:bg-indigo-500/20 border border-white/5 hover:border-indigo-500/30 transition-all"><i data-lucide="github" class="w-4 h-4"></i></a>
                    </div>
                </div>
                
                <!-- Links -->
                <div class="md:col-span-4 md:col-start-9 flex flex-col md:items-end">
                    <h4 class="text-white font-semibold mb-6">Quick Links</h4>
                    <ul class="space-y-4 md:text-right">
                        <li><a href="#features" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">How it Works</a></li>
                        <li><a href="#about" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">About Us</a></li>
                        <li><a href="auth/login.php" class="text-sm text-slate-400 hover:text-indigo-400 transition-colors">Access Dashboard</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs font-medium tracking-wide flex items-center gap-2">
                    &copy; <?php echo date('Y'); ?> Companio AI. Crafted with <i data-lucide="heart" class="w-3 h-3 text-rose-500 fill-rose-500/20"></i> for Caregivers.
                </p>
                <div class="flex gap-6 text-xs text-slate-500">
                    <a href="#" class="hover:text-slate-300 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-300 transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Icons
        lucide.createIcons();
    </script>
</body>
</html>
"use client";

import { useState } from "react";
import Link from "next/link";
import {
  Shield,
  Zap,
  CheckCircle2,
  ArrowRight,
  Sparkles,
  Layers,
  Users,
  GitMerge,
  Receipt,
  BookOpen,
  Package,
  ShoppingCart,
  Store,
  UserPlus,
  Target,
  CheckSquare,
  LifeBuoy,
  Briefcase,
  Globe,
  Lock,
  ChevronDown,
  TrendingUp,
  Cpu,
  BarChart3,
  Check,
  Star,
} from "lucide-react";

export default function LandingPage() {
  const [billingCycle, setBillingCycle] = useState<"monthly" | "annual">("annual");
  const [openFaq, setOpenFaq] = useState<number | null>(0);

  const modules = [
    { name: "Clients Directory", icon: Users, desc: "Corporate account profiles, primary contact management, and VAT tracking.", color: "from-cyan-500/20 to-blue-500/20 text-cyan-400 border-cyan-500/30" },
    { name: "Leads Pipeline", icon: GitMerge, desc: "Kanban & list views for sales leads with 1-click conversion to active clients.", color: "from-indigo-500/20 to-purple-500/20 text-indigo-400 border-indigo-500/30" },
    { name: "Invoices & Billing", icon: Receipt, desc: "Automated billing, line-item adjustments, and double-entry posting integration.", color: "from-emerald-500/20 to-teal-500/20 text-emerald-400 border-emerald-500/30" },
    { name: "Double-Entry Accounting", icon: BookOpen, desc: "Chart of Accounts, journal entries, balance sheet, and live profit/loss summaries.", color: "from-amber-500/20 to-orange-500/20 text-amber-400 border-amber-500/30" },
    { name: "Warehouse & Inventory", icon: Package, desc: "Multi-depot stock management, SKU tracking, and minimum inventory alerts.", color: "from-purple-500/20 to-pink-500/20 text-purple-400 border-purple-500/30" },
    { name: "Procurement & POs", icon: ShoppingCart, desc: "Vendor management, purchase order approvals, and stock receipt workflows.", color: "from-blue-500/20 to-cyan-500/20 text-blue-400 border-blue-500/30" },
    { name: "WooCommerce Connector", icon: Store, desc: "Real-time bi-directional sync for online orders, customers, and stock levels.", color: "from-rose-500/20 to-red-500/20 text-rose-400 border-rose-500/30" },
    { name: "OKRs & Goal Tracking", icon: Target, desc: "Strategic objectives, quantifiable key results, and confidence scoring.", color: "from-cyan-500/20 to-emerald-500/20 text-cyan-300 border-cyan-500/30" },
  ];

  const faqs = [
    {
      q: "What is Perfex CRM Modernized 2026?",
      a: "It is a complete enterprise rebuild of the classic Perfex CRM architecture, powered by a high-performance Python FastAPI async backend and a Next.js 16 App Router frontend with React 19."
    },
    {
      q: "How does the FastAPI Backend connect with Next.js?",
      a: "The frontend interacts with FastAPI RESTful API v1 endpoints using Axios, React Query for caching, and HttpOnly cookies paired with JWT Bearer tokens for secure authentication."
    },
    {
      q: "Can I run this without installing PostgreSQL locally?",
      a: "Yes! The backend infrastructure contains a dual DB engine that automatically falls back to an async SQLite database (perfexcrm.db) if local PostgreSQL is offline, ensuring zero-friction local execution."
    },
    {
      q: "Which custom Perfex CRM modules are supported?",
      a: "All 16 core & custom modules are fully supported out of the box: Accounting, Warehouse, Purchase, Recruitment, WooCommerce, OKRs, Account Planning, Staff Outsourcing, Tickets, Estimates, and Settings."
    }
  ];

  return (
    <div className="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-cyan-500 selection:text-slate-950 relative overflow-hidden">
      {/* Background Ambient Glows */}
      <div className="absolute top-0 right-1/4 w-[600px] h-[600px] bg-gradient-to-br from-cyan-500/10 via-indigo-500/10 to-transparent rounded-full blur-[140px] pointer-events-none"></div>
      <div className="absolute top-1/3 left-0 w-[500px] h-[500px] bg-purple-500/10 rounded-full blur-[140px] pointer-events-none"></div>
      <div className="absolute bottom-0 right-0 w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[140px] pointer-events-none"></div>

      {/* Top Glass Navbar */}
      <header className="sticky top-0 z-50 backdrop-blur-xl bg-slate-950/80 border-b border-slate-800/80">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="h-10 w-10 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center shadow-lg shadow-cyan-500/25">
              <Shield className="h-6 w-6 text-slate-950" />
            </div>
            <div>
              <span className="text-xl font-black tracking-tight text-white flex items-center gap-2">
                Perfex CRM <span className="text-xs px-2 py-0.5 rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 font-bold">2026 Edition</span>
              </span>
              <span className="text-[10px] text-slate-400 block -mt-1 font-semibold uppercase tracking-wider">FastAPI & Next.js 16</span>
            </div>
          </div>

          <nav className="hidden md:flex items-center gap-8 text-xs font-bold text-slate-300">
            <a href="#features" className="hover:text-cyan-400 transition-colors">Features</a>
            <a href="#modules" className="hover:text-cyan-400 transition-colors">Modules</a>
            <a href="#architecture" className="hover:text-cyan-400 transition-colors">Architecture</a>
            <a href="#pricing" className="hover:text-cyan-400 transition-colors">Pricing</a>
            <a href="#faq" className="hover:text-cyan-400 transition-colors">FAQ</a>
          </nav>

          <div className="flex items-center gap-3">
            <Link
              href="/login"
              className="px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-900/80 hover:bg-slate-800 text-xs font-bold text-slate-200 transition-all"
            >
              Sign In
            </Link>
            <Link
              href="/"
              className="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-slate-950 text-xs font-extrabold shadow-lg shadow-cyan-500/25 transition-all cursor-pointer"
            >
              <Sparkles className="h-4 w-4" /> Live Dashboard
            </Link>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="relative pt-20 pb-24 lg:pt-28 lg:pb-32 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-900/90 border border-cyan-500/30 text-cyan-400 text-xs font-bold mb-8 shadow-xl backdrop-blur-md animate-bounce">
          <Sparkles className="h-4 w-4 text-cyan-400" />
          <span>Next-Generation Enterprise CRM & ERP Platform</span>
        </div>

        <h1 className="text-4xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight leading-[1.1] max-w-5xl mx-auto">
          Modernize Your Business Operations with{" "}
          <span className="bg-gradient-to-r from-cyan-400 via-teal-300 to-indigo-400 bg-clip-text text-transparent">
            AI-Ready Architecture
          </span>
        </h1>

        <p className="mt-6 text-base sm:text-lg text-slate-400 max-w-3xl mx-auto font-normal leading-relaxed">
          Experience 10x faster response times with Python FastAPI Clean Architecture backend and Next.js 16 modern UI. 
          Pre-integrated with 16 enterprise CRM, Accounting, Warehouse, and Strategy modules out of the box.
        </p>

        <div className="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
          <Link
            href="/"
            className="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 via-teal-400 to-blue-500 text-slate-950 font-black text-sm shadow-xl shadow-cyan-500/30 hover:scale-105 transition-all flex items-center justify-center gap-2 cursor-pointer"
          >
            Open Live CRM Portal <ArrowRight className="h-4 w-4" />
          </Link>
          <a
            href="#modules"
            className="w-full sm:w-auto px-8 py-4 rounded-2xl bg-slate-900/90 border border-slate-800 hover:border-slate-700 text-slate-200 font-bold text-sm shadow-xl hover:bg-slate-800/80 transition-all flex items-center justify-center gap-2"
          >
            Explore 16 Modules <Layers className="h-4 w-4 text-cyan-400" />
          </a>
        </div>

        {/* Hero Interactive Preview Card */}
        <div className="mt-16 relative mx-auto max-w-5xl rounded-3xl border border-slate-800 bg-slate-900/60 p-4 sm:p-6 shadow-2xl backdrop-blur-2xl">
          <div className="flex items-center justify-between border-b border-slate-800/80 pb-4 mb-6">
            <div className="flex items-center gap-2">
              <div className="h-3 w-3 rounded-full bg-rose-500"></div>
              <div className="h-3 w-3 rounded-full bg-amber-500"></div>
              <div className="h-3 w-3 rounded-full bg-emerald-500"></div>
              <span className="ml-2 text-xs font-mono text-slate-400">perfexcrm.modernized.app / api / v1</span>
            </div>
            <span className="text-[11px] font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20 flex items-center gap-1.5">
              <span className="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span> FastAPI Engine Active
            </span>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-left">
            <div className="bg-slate-950/80 border border-slate-800 p-4 rounded-2xl">
              <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Revenue</span>
              <h3 className="text-xl font-black text-white mt-1">฿148,920.00</h3>
              <span className="text-[11px] font-bold text-emerald-400 flex items-center gap-1 mt-1">
                <TrendingUp className="h-3 w-3" /> +14.2% YoY
              </span>
            </div>
            <div className="bg-slate-950/80 border border-slate-800 p-4 rounded-2xl">
              <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">Active Clients</span>
              <h3 className="text-xl font-black text-white mt-1">48 Corporate</h3>
              <span className="text-[11px] font-bold text-cyan-400 flex items-center gap-1 mt-1">
                <CheckCircle2 className="h-3 w-3" /> +4 this month
              </span>
            </div>
            <div className="bg-slate-950/80 border border-slate-800 p-4 rounded-2xl">
              <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">Win Conversion</span>
              <h3 className="text-xl font-black text-white mt-1">68.4%</h3>
              <span className="text-[11px] font-bold text-purple-400 flex items-center gap-1 mt-1">
                <BarChart3 className="h-3 w-3" /> 27 Open Leads
              </span>
            </div>
            <div className="bg-slate-950/80 border border-slate-800 p-4 rounded-2xl">
              <span className="text-[10px] font-bold uppercase tracking-wider text-slate-400">API Response</span>
              <h3 className="text-xl font-black text-emerald-400 mt-1">24 ms</h3>
              <span className="text-[11px] font-bold text-slate-400 flex items-center gap-1 mt-1">
                <Cpu className="h-3 w-3 text-cyan-400" /> FastAPI Async
              </span>
            </div>
          </div>
        </div>
      </section>

      {/* Feature Highlights Grid */}
      <section id="features" className="py-20 border-t border-slate-800/80 bg-slate-950/50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-3xl mx-auto mb-16">
            <h2 className="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">Core Technology Highlights</h2>
            <h3 className="text-3xl sm:text-4xl font-black text-white tracking-tight">Engineered for Enterprise Performance</h3>
            <p className="text-xs sm:text-sm text-slate-400 mt-3">Clean Architecture separating domain models, application schemas, infrastructure, and FastAPI REST endpoints.</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-slate-900/60 border border-slate-800 p-8 rounded-3xl hover:border-slate-700 transition-all shadow-xl">
              <div className="h-12 w-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/20 mb-6">
                <Zap className="h-6 w-6" />
              </div>
              <h4 className="text-lg font-extrabold text-white mb-2">Python FastAPI Backend</h4>
              <p className="text-xs text-slate-400 leading-relaxed">
                Async execution with Pydantic v2 type checking, dual DB engine (SQLite fallback + PostgreSQL), and JWT HTTP-only cookie authentication.
              </p>
            </div>

            <div className="bg-slate-900/60 border border-slate-800 p-8 rounded-3xl hover:border-slate-700 transition-all shadow-xl">
              <div className="h-12 w-12 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/20 mb-6">
                <Globe className="h-6 w-6" />
              </div>
              <h4 className="text-lg font-extrabold text-white mb-2">Next.js 16 & React 19</h4>
              <p className="text-xs text-slate-400 leading-relaxed">
                Bespoke dark-mode UI design system, React Query server caching, glassmorphic layout components, and custom theme switcher.
              </p>
            </div>

            <div className="bg-slate-900/60 border border-slate-800 p-8 rounded-3xl hover:border-slate-700 transition-all shadow-xl">
              <div className="h-12 w-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/20 mb-6">
                <Lock className="h-6 w-6" />
              </div>
              <h4 className="text-lg font-extrabold text-white mb-2">Double-Entry Financial Engine</h4>
              <p className="text-xs text-slate-400 leading-relaxed">
                Integrated Chart of Accounts posting engine for invoice payments, automatic ledger balance updates, and live balance sheet metrics.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Modules Grid Section */}
      <section id="modules" className="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center max-w-3xl mx-auto mb-16">
          <h2 className="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">100% Fully Supported Modules</h2>
          <h3 className="text-3xl sm:text-4xl font-black text-white tracking-tight">Everything Your Enterprise Needs</h3>
          <p className="text-xs sm:text-sm text-slate-400 mt-3">From CRM and billing to warehouse inventory, WooCommerce sync, and OKRs.</p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {modules.map((m) => {
            const Icon = m.icon;
            return (
              <div
                key={m.name}
                className="bg-slate-900/60 border border-slate-800 hover:border-slate-700 rounded-3xl p-6 shadow-xl flex flex-col justify-between group transition-all duration-300"
              >
                <div>
                  <div className={`h-10 w-10 rounded-xl bg-gradient-to-br ${m.color} border flex items-center justify-center mb-4 group-hover:scale-110 transition-transform`}>
                    <Icon className="h-5 w-5" />
                  </div>
                  <h4 className="text-base font-extrabold text-white mb-2">{m.name}</h4>
                  <p className="text-xs text-slate-400 leading-relaxed">{m.desc}</p>
                </div>
                <div className="mt-6 pt-4 border-t border-slate-800/80 flex items-center justify-between text-[11px] font-bold text-cyan-400">
                  <span>FastAPI Endpoint Ready</span>
                  <CheckCircle2 className="h-3.5 w-3.5" />
                </div>
              </div>
            );
          })}
        </div>
      </section>

      {/* Architecture Comparison Section */}
      <section id="architecture" className="py-20 border-t border-slate-800/80 bg-slate-950/60">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <span className="text-xs font-bold uppercase tracking-widest text-cyan-400">Architecture Shift</span>
              <h3 className="text-3xl font-black text-white tracking-tight mt-2 mb-6">
                Legacy Monolith vs Modern Clean Architecture
              </h3>
              <div className="space-y-4 text-xs">
                <div className="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex items-start gap-4">
                  <div className="h-8 w-8 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <Check className="h-4 w-4" />
                  </div>
                  <div>
                    <h5 className="font-extrabold text-slate-200">Modern Python FastAPI Backend</h5>
                    <p className="text-slate-400 mt-1">Clean layer separation (Domain ORM, Pydantic v2 schemas, services layer, REST v1 endpoints).</p>
                  </div>
                </div>
                <div className="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex items-start gap-4">
                  <div className="h-8 w-8 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <Check className="h-4 w-4" />
                  </div>
                  <div>
                    <h5 className="font-extrabold text-slate-200">Next.js 16 App Router Frontend</h5>
                    <p className="text-slate-400 mt-1">High speed client components, TanStack React Query cache management, and Tailwind dark theme UI.</p>
                  </div>
                </div>
                <div className="p-4 rounded-2xl bg-slate-900 border border-slate-800 flex items-start gap-4">
                  <div className="h-8 w-8 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0">
                    <Check className="h-4 w-4" />
                  </div>
                  <div>
                    <h5 className="font-extrabold text-slate-200">Zero-Friction Fallback</h5>
                    <p className="text-slate-400 mt-1">Automatic SQLite fallback so development runs anywhere without mandatory local PostgreSQL configuration.</p>
                  </div>
                </div>
              </div>
            </div>

            <div className="bg-slate-900/80 border border-slate-800 p-8 rounded-3xl shadow-2xl">
              <h4 className="text-sm font-extrabold text-white uppercase tracking-wider mb-6">Performance Benchmark</h4>
              <div className="space-y-6 text-xs">
                <div>
                  <div className="flex justify-between font-bold mb-2">
                    <span className="text-slate-300">FastAPI Async API Response</span>
                    <span className="text-emerald-400">24 ms (10x faster)</span>
                  </div>
                  <div className="w-full bg-slate-950 h-3 rounded-full overflow-hidden p-0.5 border border-slate-800">
                    <div className="bg-emerald-400 h-full rounded-full w-[90%]"></div>
                  </div>
                </div>
                <div>
                  <div className="flex justify-between font-bold mb-2">
                    <span className="text-slate-300">Traditional Monolith Sync PHP</span>
                    <span className="text-slate-400">320 ms</span>
                  </div>
                  <div className="w-full bg-slate-950 h-3 rounded-full overflow-hidden p-0.5 border border-slate-800">
                    <div className="bg-slate-700 h-full rounded-full w-[25%]"></div>
                  </div>
                </div>
                <div>
                  <div className="flex justify-between font-bold mb-2">
                    <span className="text-slate-300">Next.js 16 UI First Contentful Paint</span>
                    <span className="text-cyan-400">0.4 s</span>
                  </div>
                  <div className="w-full bg-slate-950 h-3 rounded-full overflow-hidden p-0.5 border border-slate-800">
                    <div className="bg-cyan-400 h-full rounded-full w-[95%]"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Pricing Section */}
      <section id="pricing" className="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div className="max-w-3xl mx-auto mb-12">
          <h2 className="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">Flexible Subscription Plans</h2>
          <h3 className="text-3xl sm:text-4xl font-black text-white tracking-tight">Transparent Pricing for Growing Teams</h3>
          <p className="text-xs sm:text-sm text-slate-400 mt-3">Choose the plan that fits your business requirements.</p>

          <div className="mt-8 inline-flex items-center bg-slate-900 p-1.5 rounded-2xl border border-slate-800 text-xs font-bold">
            <button
              onClick={() => setBillingCycle("monthly")}
              className={`px-4 py-2 rounded-xl transition-all ${billingCycle === "monthly" ? "bg-cyan-500 text-slate-950" : "text-slate-400"}`}
            >
              Monthly Billing
            </button>
            <button
              onClick={() => setBillingCycle("annual")}
              className={`px-4 py-2 rounded-xl transition-all ${billingCycle === "annual" ? "bg-cyan-500 text-slate-950" : "text-slate-400"}`}
            >
              Annual (Save 20%)
            </button>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">
          {/* Starter */}
          <div className="bg-slate-900/60 border border-slate-800 p-8 rounded-3xl shadow-xl flex flex-col justify-between">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Professional Starter</span>
              <h4 className="text-3xl font-black text-white mt-2 mb-4">
                {billingCycle === "annual" ? "฿2,900" : "฿3,500"}{" "}
                <span className="text-xs font-normal text-slate-400">/ month</span>
              </h4>
              <p className="text-xs text-slate-400 mb-6">Ideal for small tech firms and startups.</p>
              <ul className="space-y-3 text-xs text-slate-300">
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Up to 15 User Licenses</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Clients & Leads Module</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Invoices & Proposals</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> FastAPI SQLite Engine</li>
              </ul>
            </div>
            <Link href="/login" className="mt-8 w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs text-center transition-all">
              Start Free Trial
            </Link>
          </div>

          {/* Enterprise - Featured */}
          <div className="bg-slate-900/90 border-2 border-cyan-500/60 p-8 rounded-3xl shadow-2xl shadow-cyan-500/10 flex flex-col justify-between relative">
            <span className="absolute -top-3.5 right-6 px-3 py-1 rounded-full bg-cyan-500 text-slate-950 text-[10px] font-extrabold uppercase tracking-wider">
              Most Popular
            </span>
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-cyan-400">Enterprise Suite</span>
              <h4 className="text-3xl font-black text-white mt-2 mb-4">
                {billingCycle === "annual" ? "฿7,900" : "฿9,500"}{" "}
                <span className="text-xs font-normal text-slate-400">/ month</span>
              </h4>
              <p className="text-xs text-slate-400 mb-6">Complete suite for growing corporate businesses.</p>
              <ul className="space-y-3 text-xs text-slate-300">
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Unlimited Staff Accounts</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> All 16 Enterprise Modules</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> PostgreSQL + Redis Async Sync</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> WooCommerce Bi-Directional Sync</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Double-Entry Chart of Accounts</li>
              </ul>
            </div>
            <Link href="/" className="mt-8 w-full py-3.5 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black text-xs text-center shadow-lg shadow-cyan-500/25 transition-all">
              Launch Enterprise Portal
            </Link>
          </div>

          {/* Custom Multi-Tenant */}
          <div className="bg-slate-900/60 border border-slate-800 p-8 rounded-3xl shadow-xl flex flex-col justify-between">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-slate-400">Dedicated Cloud</span>
              <h4 className="text-3xl font-black text-white mt-2 mb-4">Custom</h4>
              <p className="text-xs text-slate-400 mb-6">Dedicated deployment & custom module development.</p>
              <ul className="space-y-3 text-xs text-slate-300">
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Private Cloud / On-Premise</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Custom FastAPI Microservices</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> SLA 99.95% Uptime Guarantee</li>
                <li className="flex items-center gap-2"><Check className="h-4 w-4 text-cyan-400" /> Dedicated Account Manager</li>
              </ul>
            </div>
            <a href="mailto:support@perfexcrm.com" className="mt-8 w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs text-center transition-all">
              Contact Sales
            </a>
          </div>
        </div>
      </section>

      {/* FAQ Accordion Section */}
      <section id="faq" className="py-20 border-t border-slate-800/80 bg-slate-950/40">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">Got Questions?</h2>
            <h3 className="text-3xl font-black text-white tracking-tight">Frequently Asked Questions</h3>
          </div>

          <div className="space-y-4">
            {faqs.map((faq, i) => (
              <div key={i} className="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden">
                <button
                  onClick={() => setOpenFaq(openFaq === i ? null : i)}
                  className="w-full p-5 text-left font-bold text-sm text-slate-200 flex items-center justify-between"
                >
                  <span>{faq.q}</span>
                  <ChevronDown className={`h-4 w-4 text-cyan-400 transition-transform ${openFaq === i ? "rotate-180" : ""}`} />
                </button>
                {openFaq === i && (
                  <div className="px-5 pb-5 text-xs text-slate-400 leading-relaxed border-t border-slate-800/60 pt-3">
                    {faq.a}
                  </div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="border-t border-slate-800/80 py-12 bg-slate-950 text-slate-400 text-xs">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-6">
          <div className="flex items-center gap-3">
            <div className="h-8 w-8 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center">
              <Shield className="h-5 w-5 text-slate-950" />
            </div>
            <span className="font-extrabold text-white text-sm">Perfex CRM 2026</span>
          </div>

          <div className="flex items-center gap-6 font-semibold">
            <Link href="/" className="hover:text-white">Dashboard</Link>
            <Link href="/login" className="hover:text-white">Sign In</Link>
            <a href="#features" className="hover:text-white">Features</a>
            <a href="#pricing" className="hover:text-white">Pricing</a>
          </div>

          <p>© 2026 Perfex CRM Modernized. Built with Python FastAPI & Next.js 16.</p>
        </div>
      </footer>
    </div>
  );
}

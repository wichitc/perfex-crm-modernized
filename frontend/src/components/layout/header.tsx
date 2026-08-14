"use client";

import { useState } from "react";
import { usePerfexTheme, ThemeMode } from "@/providers/theme-provider";
import { useTranslation, LanguageCode } from "@/providers/language-provider";
import {
  Search,
  Bell,
  Palette,
  User,
  LogOut,
  ChevronDown,
  Sparkles,
  Globe,
  Check,
  Languages,
} from "lucide-react";
import Link from "next/link";
import { MOCK_USER } from "@/lib/mock-data";

export default function Header() {
  const { theme, setTheme, themeConfig } = usePerfexTheme();
  const { language, setLanguage, t } = useTranslation();

  const [isThemeMenuOpen, setIsThemeMenuOpen] = useState(false);
  const [isLangMenuOpen, setIsLangMenuOpen] = useState(false);
  const [isNotificationsOpen, setIsNotificationsOpen] = useState(false);
  const [isUserMenuOpen, setIsUserMenuOpen] = useState(false);

  const themes: { id: ThemeMode; name: string; color: string }[] = [
    { id: "teal", name: "NOVIXA Classic Teal", color: "#28b8da" },
    { id: "dark", name: "Midnight Dark", color: "#6366f1" },
    { id: "light", name: "Clean Light", color: "#0284c7" },
    { id: "green", name: "Emerald Green", color: "#10b981" },
    { id: "orange", name: "Warm Sunset", color: "#f97316" },
    { id: "purple", name: "Royal Purple", color: "#8b5cf6" },
  ];

  const languages: { id: LanguageCode; name: string; flag: string }[] = [
    { id: "th", name: "ภาษาไทย", flag: "🇹🇭" },
    { id: "en", name: "English", flag: "🇬🇧" },
  ];

  return (
    <header className="h-16 border-b border-slate-800/80 bg-slate-900/60 backdrop-blur-xl px-8 flex items-center justify-between z-10">
      {/* Search Input */}
      <div className="flex items-center gap-3 w-96">
        <div className="relative w-full">
          <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-500" />
          <input
            type="text"
            placeholder={t("common.searchPlaceholder")}
            className="w-full bg-slate-950/60 border border-slate-800 text-xs text-slate-200 placeholder:text-slate-500 rounded-xl py-2 pl-9 pr-4 focus:outline-none focus:border-cyan-500/60 focus:ring-1 focus:ring-cyan-500/50 transition-all"
          />
        </div>
      </div>

      {/* Action Controls */}
      <div className="flex items-center gap-3">
        {/* Landing Page Quick Button */}
        <Link
          href="/landing"
          className="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-xs font-bold hover:bg-cyan-500/20 transition-all"
        >
          <Globe className="h-4 w-4 text-cyan-400" />
          <span>{t("common.landingPage")}</span>
        </Link>

        {/* Live Language Switcher Dropdown */}
        <div className="relative">
          <button
            onClick={() => {
              setIsLangMenuOpen(!isLangMenuOpen);
              setIsThemeMenuOpen(false);
            }}
            className="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/60 border border-slate-700/60 text-slate-300 text-xs font-semibold hover:bg-slate-800 hover:text-white transition-all cursor-pointer"
          >
            <Languages className="h-4 w-4 text-cyan-400" />
            <span>
              {language === "th" ? "🇹🇭 ไทย" : "🇬🇧 EN"}
            </span>
            <ChevronDown className="h-3.5 w-3.5 text-slate-400" />
          </button>

          {isLangMenuOpen && (
            <div className="absolute right-0 mt-2 w-44 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-2 z-50 animate-fadeIn">
              <div className="px-3 py-2 border-b border-slate-800/80 text-[10px] uppercase tracking-wider text-slate-400 font-bold flex items-center gap-1.5">
                <Globe className="h-3.5 w-3.5 text-cyan-400" />
                {t("common.selectLanguage")}
              </div>
              <div className="py-1 space-y-1">
                {languages.map((l) => (
                  <button
                    key={l.id}
                    onClick={() => {
                      setLanguage(l.id);
                      setIsLangMenuOpen(false);
                    }}
                    className={`w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors cursor-pointer ${
                      language === l.id
                        ? "bg-slate-800 text-white font-semibold"
                        : "text-slate-400 hover:text-slate-200 hover:bg-slate-800/50"
                    }`}
                  >
                    <div className="flex items-center gap-2.5">
                      <span>{l.flag}</span>
                      <span>{l.name}</span>
                    </div>
                    {language === l.id && <Check className="h-3.5 w-3.5 text-cyan-400" />}
                  </button>
                ))}
              </div>
            </div>
          )}
        </div>

        {/* Live Theme Switcher Dropdown */}
        <div className="relative">
          <button
            onClick={() => {
              setIsThemeMenuOpen(!isThemeMenuOpen);
              setIsLangMenuOpen(false);
            }}
            className="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800/60 border border-slate-700/60 text-slate-300 text-xs font-semibold hover:bg-slate-800 hover:text-white transition-all cursor-pointer"
          >
            <Palette className="h-4 w-4 text-cyan-400" />
            <span className="hidden sm:inline">{themeConfig.name}</span>
            <span
              className="h-3 w-3 rounded-full border border-white/20"
              style={{ backgroundColor: themeConfig.primary }}
            ></span>
            <ChevronDown className="h-3.5 w-3.5 text-slate-400" />
          </button>

          {isThemeMenuOpen && (
            <div className="absolute right-0 mt-2 w-56 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-2 z-50 animate-fadeIn">
              <div className="px-3 py-2 border-b border-slate-800/80 text-[10px] uppercase tracking-wider text-slate-400 font-bold flex items-center gap-1.5">
                <Sparkles className="h-3.5 w-3.5 text-cyan-400" />
                {t("common.selectTheme")}
              </div>
              <div className="py-1 space-y-1">
                {themes.map((tItem) => (
                  <button
                    key={tItem.id}
                    onClick={() => {
                      setTheme(tItem.id);
                      setIsThemeMenuOpen(false);
                    }}
                    className={`w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors cursor-pointer ${
                      theme === tItem.id
                        ? "bg-slate-800 text-white font-semibold"
                        : "text-slate-400 hover:text-slate-200 hover:bg-slate-800/50"
                    }`}
                  >
                    <div className="flex items-center gap-2.5">
                      <span
                        className="h-3.5 w-3.5 rounded-full border border-white/20 shadow-sm"
                        style={{ backgroundColor: tItem.color }}
                      ></span>
                      <span>{tItem.name}</span>
                    </div>
                    {theme === tItem.id && <Check className="h-3.5 w-3.5 text-cyan-400" />}
                  </button>
                ))}
              </div>
            </div>
          )}
        </div>

        {/* Notifications Icon */}
        <div className="relative">
          <button
            onClick={() => setIsNotificationsOpen(!isNotificationsOpen)}
            className="relative p-2 rounded-xl bg-slate-800/60 border border-slate-700/60 text-slate-400 hover:text-white transition-all cursor-pointer"
          >
            <Bell className="h-4 w-4" />
            <span className="absolute top-1.5 right-1.5 h-2 w-2 rounded-full bg-cyan-400 ring-2 ring-slate-900"></span>
          </button>

          {isNotificationsOpen && (
            <div className="absolute right-0 mt-2 w-80 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-4 z-50">
              <h4 className="text-xs font-bold text-slate-200 mb-3 uppercase tracking-wider">
                {t("common.notifications")}
              </h4>
              <div className="space-y-2 text-xs">
                <div className="p-2.5 rounded-xl bg-slate-800/40 border border-slate-800 text-slate-300">
                  <p className="font-semibold text-cyan-400">{t("notification.paymentReceived")}</p>
                  <p className="text-[11px] text-slate-400 mt-0.5">
                    {t("notification.invoicePaid", { number: "INV-2026-1001" })}
                  </p>
                </div>
                <div className="p-2.5 rounded-xl bg-slate-800/40 border border-slate-800 text-slate-300">
                  <p className="font-semibold text-emerald-400">{t("notification.newLead")}</p>
                  <p className="text-[11px] text-slate-400 mt-0.5">
                    {t("notification.leadQualified", { name: "Eastern Manufacturing" })}
                  </p>
                </div>
              </div>
            </div>
          )}
        </div>

        {/* User Profile */}
        <div className="relative">
          <button
            onClick={() => setIsUserMenuOpen(!isUserMenuOpen)}
            className="flex items-center gap-3 pl-2 pr-1.5 py-1 rounded-xl bg-slate-800/40 border border-slate-800 hover:bg-slate-800/80 transition-all cursor-pointer"
          >
            <div className="h-7 w-7 rounded-lg bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center text-white font-bold text-xs">
              SA
            </div>
            <span className="text-xs font-bold text-slate-200">{MOCK_USER.firstname}</span>
            <ChevronDown className="h-3.5 w-3.5 text-slate-400" />
          </button>

          {isUserMenuOpen && (
            <div className="absolute right-0 mt-2 w-48 rounded-2xl bg-slate-900 border border-slate-800 shadow-2xl p-2 z-50">
              <div className="px-3 py-2 border-b border-slate-800">
                <p className="text-xs font-bold text-white">{MOCK_USER.firstname} {MOCK_USER.lastname}</p>
                <p className="text-[10px] text-slate-400">{MOCK_USER.email}</p>
              </div>
              <div className="py-1">
                <Link href="/settings" className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs text-slate-300 hover:bg-slate-800">
                  <User className="h-3.5 w-3.5 text-slate-400" /> {t("common.profileSettings")}
                </Link>
                <button
                  onClick={() => {
                    localStorage.removeItem("accessToken");
                    window.location.href = "/login";
                  }}
                  className="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-xs text-rose-400 hover:bg-rose-500/10 cursor-pointer"
                >
                  <LogOut className="h-3.5 w-3.5" /> {t("common.signOut")}
                </button>
              </div>
            </div>
          )}
        </div>
      </div>
    </header>
  );
}

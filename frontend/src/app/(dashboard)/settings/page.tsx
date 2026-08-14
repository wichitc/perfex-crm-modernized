"use client";

import { usePerfexTheme, ThemeMode } from "@/providers/theme-provider";
import { useTranslation, LanguageCode } from "@/providers/language-provider";
import { Settings, Palette, Check, Building2, Languages } from "lucide-react";

export default function SettingsPage() {
  const { theme, setTheme } = usePerfexTheme();
  const { language, setLanguage, t } = useTranslation();

  const themes: { id: ThemeMode; name: string; color: string; desc: string }[] = [
    { id: "teal", name: "NOVIXA Classic Teal", color: "#28b8da", desc: "Authentic NOVIXA CRM signature color theme" },
    { id: "dark", name: "Midnight Dark", color: "#6366f1", desc: "Sleek dark mode tailored for low light" },
    { id: "light", name: "Clean Light", color: "#0284c7", desc: "Bright corporate look with high contrast" },
    { id: "green", name: "Emerald Green", color: "#10b981", desc: "Fresh vibrant green accent scheme" },
    { id: "orange", name: "Warm Sunset", color: "#f97316", desc: "Energetic warm orange color palette" },
    { id: "purple", name: "Royal Purple", color: "#8b5cf6", desc: "Premium regal purple gradients" },
  ];

  const languages: { id: LanguageCode; name: string; flag: string; desc: string }[] = [
    { id: "th", name: "ภาษาไทย (Thai)", flag: "🇹🇭", desc: "ภาษาหลักเริ่มต้นของระบบ (Default Primary)" },
    { id: "en", name: "English (US/UK)", flag: "🇬🇧", desc: "International Business English" },
  ];

  return (
    <div className="space-y-8 animate-fadeIn max-w-4xl">
      <div>
        <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
          <Settings className="h-6 w-6 text-cyan-400" />
          {t("settings.title")}
        </h2>
        <p className="text-xs text-slate-400 mt-1">
          {t("settings.subtitle")}
        </p>
      </div>

      {/* Language Selection Card */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        <div className="flex items-center gap-2 border-b border-slate-800 pb-4">
          <Languages className="h-5 w-5 text-cyan-400" />
          <h3 className="text-base font-extrabold text-white">{t("settings.localization")}</h3>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {languages.map((l) => (
            <div
              key={l.id}
              onClick={() => setLanguage(l.id)}
              className={`p-4 rounded-2xl border transition-all cursor-pointer flex items-center justify-between ${
                language === l.id
                  ? "bg-slate-800/90 border-cyan-500/60 shadow-lg shadow-cyan-500/10"
                  : "bg-slate-950/60 border-slate-800 hover:border-slate-700"
              }`}
            >
              <div className="flex items-center gap-3">
                <span className="text-2xl">{l.flag}</span>
                <div>
                  <h4 className="text-xs font-bold text-white">{l.name}</h4>
                  <p className="text-[10px] text-slate-400 mt-0.5">{l.desc}</p>
                </div>
              </div>
              {language === l.id && <Check className="h-4 w-4 text-cyan-400" />}
            </div>
          ))}
        </div>
      </div>

      {/* Theme Selection Card */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-6">
        <div className="flex items-center gap-2 border-b border-slate-800 pb-4">
          <Palette className="h-5 w-5 text-cyan-400" />
          <h3 className="text-base font-extrabold text-white">{t("settings.themeSettings")}</h3>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {themes.map((tItem) => (
            <div
              key={tItem.id}
              onClick={() => setTheme(tItem.id)}
              className={`p-4 rounded-2xl border transition-all cursor-pointer flex items-center justify-between ${
                theme === tItem.id
                  ? "bg-slate-800/90 border-cyan-500/60 shadow-lg shadow-cyan-500/10"
                  : "bg-slate-950/60 border-slate-800 hover:border-slate-700"
              }`}
            >
              <div className="flex items-center gap-3">
                <span
                  className="h-7 w-7 rounded-xl border border-white/20 shadow-md flex shrink-0"
                  style={{ backgroundColor: tItem.color }}
                ></span>
                <div>
                  <h4 className="text-xs font-bold text-white">{tItem.name}</h4>
                  <p className="text-[10px] text-slate-400 mt-0.5">{tItem.desc}</p>
                </div>
              </div>
              {theme === tItem.id && <Check className="h-4 w-4 text-cyan-400" />}
            </div>
          ))}
        </div>
      </div>

      {/* Company Info Mock Card */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl p-6 shadow-xl space-y-5">
        <div className="flex items-center gap-2 border-b border-slate-800 pb-4">
          <Building2 className="h-5 w-5 text-cyan-400" />
          <h3 className="text-base font-extrabold text-white">{t("settings.companyInfo")}</h3>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
          <div>
            <label className="text-slate-300 font-semibold block mb-1">{t("customer.company")}</label>
            <input
              type="text"
              defaultValue="NOVIXA CRM Solutions Thailand"
              className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white"
            />
          </div>
          <div>
            <label className="text-slate-300 font-semibold block mb-1">{t("settings.defaultCurrency")}</label>
            <input
              type="text"
              defaultValue="THB (฿)"
              className="w-full bg-slate-950 border border-slate-800 rounded-xl py-2 px-3 text-white"
            />
          </div>
        </div>
      </div>
    </div>
  );
}

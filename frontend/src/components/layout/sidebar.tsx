"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { usePerfexTheme } from "@/providers/theme-provider";
import { useTranslation } from "@/providers/language-provider";
import {
  Shield,
  LayoutDashboard,
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
  Layers,
  Globe,
  Settings,
  ChevronRight,
} from "lucide-react";

export default function Sidebar() {
  const pathname = usePathname();
  const { themeConfig } = usePerfexTheme();
  const { t } = useTranslation();

  const menuItems = [
    { name: t("navigation.menu.dashboard"), href: "/", icon: LayoutDashboard },
    { name: t("navigation.menu.landing"), href: "/landing", icon: Globe },
    { name: t("navigation.menu.clients"), href: "/clients", icon: Users },
    { name: t("navigation.menu.leads"), href: "/leads", icon: GitMerge },
    { name: t("navigation.menu.invoices"), href: "/invoices", icon: Receipt },
    { name: t("navigation.menu.estimates"), href: "/estimates", icon: Layers },
    { name: t("navigation.menu.accounting"), href: "/accounting", icon: BookOpen },
    { name: t("navigation.menu.warehouse"), href: "/warehouse", icon: Package },
    { name: t("navigation.menu.purchase"), href: "/purchase", icon: ShoppingCart },
    { name: t("navigation.menu.woocommerce"), href: "/woocommerce", icon: Store },
    { name: t("navigation.menu.recruitment"), href: "/recruitment", icon: UserPlus },
    { name: t("navigation.menu.okrs"), href: "/okrs", icon: Target },
    { name: t("navigation.menu.tasks"), href: "/tasks", icon: CheckSquare },
    { name: t("navigation.menu.tickets"), href: "/tickets", icon: LifeBuoy },
    { name: t("navigation.menu.outsourcing"), href: "/staff-outsourcing", icon: Briefcase },
    { name: t("navigation.menu.account_planning"), href: "/account-planning", icon: Target },
    { name: t("navigation.menu.settings"), href: "/settings", icon: Settings },
  ];

  return (
    <aside className="w-72 border-r border-slate-800/80 bg-slate-900/90 backdrop-blur-xl flex flex-col justify-between p-5 z-20 shrink-0 select-none">
      <div className="space-y-6">
        {/* Brand Header */}
        <div className="flex items-center gap-3 px-2 py-1">
          <div
            className={`flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br ${themeConfig.primaryBg} shadow-lg shadow-cyan-500/20`}
          >
            <Shield className="h-6 w-6 text-white" />
          </div>
          <div>
            <h1 className="text-lg font-black tracking-tight text-white flex items-center gap-1.5">
              NOVIXA CRM
              <span className="text-[10px] bg-cyan-500/20 text-cyan-400 font-bold px-1.5 py-0.5 rounded-md border border-cyan-500/30">
                v2026
              </span>
            </h1>
            <p className="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">
              Next.js 16 Enterprise
            </p>
          </div>
        </div>

        {/* Navigation List */}
        <nav className="space-y-1 overflow-y-auto max-h-[calc(100vh-210px)] pr-1 custom-scrollbar">
          {menuItems.map((item) => {
            const Icon = item.icon;
            const isActive = pathname === item.href;
            return (
              <Link
                key={item.href}
                href={item.href}
                className={`flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-200 group ${
                  isActive
                    ? "bg-slate-800/90 text-white border border-slate-700/80 shadow-md"
                    : "text-slate-400 hover:text-slate-100 hover:bg-slate-800/40 border border-transparent"
                }`}
              >
                <div className="flex items-center gap-3">
                  <Icon
                    className={`h-4.5 w-4.5 transition-colors ${
                      isActive ? "text-cyan-400" : "text-slate-500 group-hover:text-slate-300"
                    }`}
                  />
                  <span>{item.name}</span>
                </div>
                {isActive && (
                  <ChevronRight className="h-3.5 w-3.5 text-cyan-400 animate-pulse" />
                )}
              </Link>
            );
          })}
        </nav>
      </div>

      {/* Footer info badge */}
      <div className="border-t border-slate-800/80 pt-4 px-2">
        <div className="flex items-center justify-between text-[11px] text-slate-400">
          <span className="flex items-center gap-1.5">
            <span className="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span>
            {t("common.systemOperational")}
          </span>
          <span className="font-mono text-slate-500">React 19</span>
        </div>
      </div>
    </aside>
  );
}

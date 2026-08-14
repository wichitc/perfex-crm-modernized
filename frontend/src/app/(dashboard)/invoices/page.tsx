"use client";

import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { apiClient } from "@/lib/api-client";
import { MOCK_INVOICES } from "@/lib/mock-data";
import { Receipt, Calendar, CreditCard, DollarSign, Plus, CheckCircle, ShieldAlert, X, Printer, Download } from "lucide-react";

export default function InvoicesPage() {
  const [selectedInvoice, setSelectedInvoice] = useState<any | null>(null);
  const [isPaymentModalOpen, setIsPaymentModalOpen] = useState(false);

  const { data: invoices } = useQuery({
    queryKey: ["invoices"],
    queryFn: async () => {
      try {
        const response = await apiClient.get("/invoices");
        return response.data;
      } catch {
        return MOCK_INVOICES;
      }
    },
    initialData: MOCK_INVOICES,
  });

  return (
    <div className="space-y-8 animate-fadeIn">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h2 className="text-2xl font-black tracking-tight text-white flex items-center gap-2">
            <Receipt className="h-6 w-6 text-amber-400" />
            Invoices & Billing
          </h2>
          <p className="text-xs text-slate-400 mt-1">
            Create, issue, and manage client tax invoices and payments.
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-lg shadow-amber-500/25 transition-all cursor-pointer">
          <Plus className="h-4 w-4" /> Create Invoice
        </button>
      </div>

      {/* Invoices List Table */}
      <div className="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden shadow-xl">
        <table className="w-full text-left text-xs">
          <thead className="bg-slate-950/80 text-slate-400 uppercase tracking-wider font-bold border-b border-slate-800">
            <tr>
              <th className="p-4">Invoice #</th>
              <th className="p-4">Client</th>
              <th className="p-4">Date</th>
              <th className="p-4">Due Date</th>
              <th className="p-4">Total Amount</th>
              <th className="p-4">Status</th>
              <th className="p-4 text-right">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800/60 text-slate-200">
            {invoices.map((inv: any) => (
              <tr key={inv.id} className="hover:bg-slate-800/40 transition-colors">
                <td className="p-4 font-bold text-white">
                  {inv.prefix}{inv.number}
                </td>
                <td className="p-4 font-semibold text-slate-200">{inv.clientName}</td>
                <td className="p-4 text-slate-400">{inv.date}</td>
                <td className="p-4 text-slate-400">{inv.duedate}</td>
                <td className="p-4 font-bold text-amber-400">฿{inv.total.toLocaleString()}</td>
                <td className="p-4">
                  <span
                    className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${
                      inv.status === 2
                        ? "bg-emerald-500/10 text-emerald-400 border-emerald-500/20"
                        : inv.status === 1
                        ? "bg-amber-500/10 text-amber-400 border-amber-500/20"
                        : "bg-rose-500/10 text-rose-400 border-rose-500/20"
                    }`}
                  >
                    {inv.status === 2 ? "Paid" : inv.status === 1 ? "Unpaid" : "Overdue"}
                  </span>
                </td>
                <td className="p-4 text-right space-x-2">
                  <button
                    onClick={() => setSelectedInvoice(inv)}
                    className="px-3 py-1.5 rounded-xl bg-slate-800 text-slate-200 text-xs font-semibold hover:bg-slate-700 cursor-pointer"
                  >
                    View / Print
                  </button>
                  {inv.status !== 2 && (
                    <button
                      onClick={() => {
                        setSelectedInvoice(inv);
                        setIsPaymentModalOpen(true);
                      }}
                      className="px-3 py-1.5 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold hover:bg-emerald-500/30 cursor-pointer"
                    >
                      Record Payment
                    </button>
                  )}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Invoice Detail / Print Preview Modal */}
      {selectedInvoice && !isPaymentModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4 animate-fadeIn">
          <div className="bg-slate-900 border border-slate-800 rounded-3xl p-8 w-full max-w-2xl shadow-2xl space-y-6">
            <div className="flex items-center justify-between border-b border-slate-800 pb-4">
              <div>
                <h3 className="text-xl font-extrabold text-white">
                  INVOICE {selectedInvoice.prefix}{selectedInvoice.number}
                </h3>
                <p className="text-xs text-slate-400 mt-0.5">Billed to: {selectedInvoice.clientName}</p>
              </div>
              <button onClick={() => setSelectedInvoice(null)} className="text-slate-400 hover:text-white">
                <X className="h-6 w-6" />
              </button>
            </div>

            <div className="grid grid-cols-2 gap-4 text-xs bg-slate-950/60 p-4 rounded-2xl border border-slate-800">
              <div>
                <span className="text-slate-500 block">Issue Date</span>
                <span className="font-bold text-slate-200">{selectedInvoice.date}</span>
              </div>
              <div>
                <span className="text-slate-500 block">Due Date</span>
                <span className="font-bold text-slate-200">{selectedInvoice.duedate}</span>
              </div>
            </div>

            {/* Line Items */}
            <div className="space-y-3">
              <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider">Itemized Breakdown</h4>
              <div className="bg-slate-950/60 rounded-2xl p-4 border border-slate-800 text-xs space-y-2">
                {selectedInvoice.items.map((item: any) => (
                  <div key={item.id} className="flex justify-between items-center py-1">
                    <div>
                      <p className="font-bold text-white">{item.description}</p>
                      <p className="text-[10px] text-slate-400">{item.long_description}</p>
                    </div>
                    <span className="font-extrabold text-amber-400">฿{item.rate.toLocaleString()}</span>
                  </div>
                ))}
              </div>
            </div>

            {/* Total Footer */}
            <div className="flex justify-between items-center pt-4 border-t border-slate-800">
              <span className="text-xs font-bold text-slate-400">TOTAL DUE AMOUNT</span>
              <span className="text-2xl font-black text-amber-400">฿{selectedInvoice.total.toLocaleString()}</span>
            </div>

            <div className="flex justify-end gap-3 pt-2">
              <button
                onClick={() => window.print()}
                className="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 text-slate-200 text-xs font-semibold hover:bg-slate-700"
              >
                <Printer className="h-4 w-4" /> Print / Save PDF
              </button>
              <button onClick={() => setSelectedInvoice(null)} className="px-4 py-2 rounded-xl bg-cyan-500 text-slate-950 text-xs font-bold">
                Close
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}

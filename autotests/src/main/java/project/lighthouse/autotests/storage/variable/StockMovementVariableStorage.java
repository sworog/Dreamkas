package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.api.objects.stockmovement.StockMovement;
import project.lighthouse.autotests.api.objects.stockmovement.invoice.Invoice;
import project.lighthouse.autotests.api.objects.stockmovement.stockin.StockIn;
import project.lighthouse.autotests.api.objects.stockmovement.supplierReturn.SupplierReturn;
import project.lighthouse.autotests.api.objects.stockmovement.writeoff.WriteOff;

import java.util.LinkedList;

public class StockMovementVariableStorage {

    private LinkedList<Invoice> invoices = new LinkedList<>();
    private LinkedList<WriteOff> writeOffs = new LinkedList<>();
    private LinkedList<StockIn> stockIns = new LinkedList<>();
    private LinkedList<SupplierReturn> supplierReturns = new LinkedList<>();

    public void addStockMovement(Invoice invoice) {
        invoices.add(invoice);
    }

    public void addStockMovement(WriteOff writeOff) {
        writeOffs.add(writeOff);
    }

    public void addStockMovement(StockIn stockIn) {
        stockIns.add(stockIn);
    }

    public void addSupplierReturn(SupplierReturn supplierReturn) {
        supplierReturns.add(supplierReturn);
    }

    public Invoice getLastInvoice() {
        return invoices.getLast();
    }

    public WriteOff getLastWriteOff() {
        return writeOffs.getLast();
    }

    public StockIn getLastStockIn() {
        return stockIns.getLast();
    }

    public SupplierReturn getLastSupplierReturn() {
        return supplierReturns.getLast();
    }
}

package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.api.objects.stockmovement.stockin.StockIn;
import project.lighthouse.autotests.api.objects.stockmovement.writeoff.WriteOff;

import java.util.LinkedList;

public class StockMovementVariableStorage {

    private LinkedList<WriteOff> writeOffs = new LinkedList<>();
    private LinkedList<StockIn> stockIns = new LinkedList<>();

    public void addWriteOff(WriteOff writeOff) {
        writeOffs.add(writeOff);
    }

    public WriteOff getLastWriteOff() {
        return writeOffs.getLast();
    }

    public void addStockIn(StockIn stockIn) {
        stockIns.add(stockIn);
    }

    public StockIn getLastStockIn() {
        return stockIns.getLast();
    }
}

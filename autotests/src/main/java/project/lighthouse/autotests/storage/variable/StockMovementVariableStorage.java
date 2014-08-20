package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.objects.api.writeoff.WriteOff;

import java.util.LinkedList;

public class StockMovementVariableStorage {

    private LinkedList<WriteOff> writeOffs = new LinkedList<>();

    public void addWriteOff(WriteOff writeOff) {
        writeOffs.add(writeOff);
    }

    public WriteOff getLastWriteOff() {
        return writeOffs.getLast();
    }
}

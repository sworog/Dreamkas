package ru.dreamkas.pos.remoteCommand;

public enum Status {
    UNKNOWN(0),
    STARTED(1),
    IN_PROGRESS(2),
    SUCCESS(3),
    FAILED(4);

    private int value;

    private Status(int value) {
        this.value = value;
    }

    static Status fromValue(int value) {
        for (Status my: Status.values()) {
            if (my.value == value) {
                return my;
            }
        }

        return null;
    }

    int value() {
        return value;
    }
}

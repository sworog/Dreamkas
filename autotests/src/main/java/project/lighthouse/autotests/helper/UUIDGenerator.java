package project.lighthouse.autotests.helper;

import java.util.UUID;

public class UUIDGenerator {

    public String generate() {
        return UUID.randomUUID().toString();
    }

    public static String generateWithoutHyphens() {
        return UUID.randomUUID().toString().replace("-", "");
    }
}

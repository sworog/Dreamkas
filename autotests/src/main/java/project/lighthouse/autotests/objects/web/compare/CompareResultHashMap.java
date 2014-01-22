package project.lighthouse.autotests.objects.web.compare;

import junit.framework.Assert;

import java.util.HashMap;
import java.util.Map;

public class CompareResultHashMap extends HashMap<Map<String, String>, CompareResults> {

    public void failIfHasAnyErrors() {
        if (!this.isEmpty()) {
            StringBuilder builder = new StringBuilder("Not found rows: \n");
            for (Map.Entry<Map<String, String>, CompareResults> entry : this.entrySet()) {
                String message = String.format("- row: '%s'\n%s", entry.getKey(), entry.getValue().getCompareRowStringResult());
                builder.append(message);
            }
            Assert.fail(builder.toString());
        }
    }
}

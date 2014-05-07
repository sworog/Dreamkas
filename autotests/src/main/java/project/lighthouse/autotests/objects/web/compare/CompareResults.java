package project.lighthouse.autotests.objects.web.compare;

import org.json.JSONException;
import project.lighthouse.autotests.StaticData;

import java.util.ArrayList;

public class CompareResults extends ArrayList<CompareResult> {

    public CompareResults compare(String rowName, String actualValue, String expectedValue) {
        String normalizedExpectedValue = normalizeExpectedValue(expectedValue);
        if (!actualValue.equals(normalizedExpectedValue)) {
            this.add(
                new CompareResult(rowName, actualValue, normalizedExpectedValue)
            );
        }
        return this;
    }

    private String normalizeExpectedValue(String expectedValue) {
        if (expectedValue.startsWith("#sku:")) {
            String name = expectedValue.substring(5);
            return StaticData.products.get(name).getSku();
        } else {
            return expectedValue;
        }
    }

    public CompareResults compareContain(String rowName, String actualValue, String expectedValue) {
        if (!actualValue.contains(expectedValue)) {
            this.add(
                    new CompareResult(rowName, actualValue, expectedValue)
            );
        }
        return this;
    }

    public String getCompareRowStringResult() {
        StringBuilder builder = new StringBuilder();
        for (CompareResult compareResult : this) {
            String message = String.format("'%s' -> Actual: '%s', Expected: '%s'", compareResult.getRowName(),
                    compareResult.getActualValue(), compareResult.getExpectedValue());
            builder.append(message).append("\n");
        }
        return builder.toString();
    }
}

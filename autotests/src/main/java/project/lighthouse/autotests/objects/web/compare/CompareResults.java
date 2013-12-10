package project.lighthouse.autotests.objects.web.compare;

import java.util.ArrayList;

public class CompareResults extends ArrayList<CompareResult> {

    public CompareResults compare(String rowName, String actualValue, String expectedValue) {
        if (!actualValue.equals(expectedValue)) {
            this.add(
                    new CompareResult(rowName, actualValue, expectedValue)
            );
        }
        return this;
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
            builder.append(message + "\n");
        }
        return builder.toString();
    }
}

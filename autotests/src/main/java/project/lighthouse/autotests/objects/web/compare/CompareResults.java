package project.lighthouse.autotests.objects.web.compare;

import org.json.JSONException;
import project.lighthouse.autotests.StaticData;

import java.util.ArrayList;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

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

    public static String normalizeExpectedValue(String expectedValue) {
        Pattern pattern = Pattern.compile("^(.*?)#sku\\[(.+?)\\](.*)$");
        Matcher matcher = pattern.matcher(expectedValue);
        if (matcher.matches()) {
            String name = matcher.group(2);
            String sku = StaticData.products.get(name).getSku();
            return matcher.group(1) + sku + matcher.group(3);
        } else if (expectedValue.startsWith("#sku:")) {
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

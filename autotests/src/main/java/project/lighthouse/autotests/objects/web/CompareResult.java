package project.lighthouse.autotests.objects.web;

public class CompareResult {

    private String rowName;

    private String actualValue;
    private String expectedValue;

    public CompareResult(String rowName, String actualValue, String expectedValue) {
        this.rowName = rowName;
        this.actualValue = actualValue;
        this.expectedValue = expectedValue;
    }

    public String getRowName() {
        return rowName;
    }

    public String getActualValue() {
        return actualValue;
    }

    public String getExpectedValue() {
        return expectedValue;
    }
}

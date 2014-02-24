package project.lighthouse.autotests.helper;

/**
 * The helper is used for generation test data for fields validation purpose
 */
public class StringGenerator {

    private int number;

    public StringGenerator(int number) {
        this.number = number;
    }

    public String generateTestData() {
        return generateTestData("a");
    }

    public String generateTestDataWithoutWhiteSpaces() {
        return generateString("a");
    }

    public String generateTestDataWithoutWhiteSpaces(String str) {
        return generateString(str);
    }

    public String generateTestData(String str) {
        String testData = generateString(str);
        StringBuilder formattedData = new StringBuilder(testData);
        for (int i = 0; i < formattedData.length(); i++) {
            if (i % 26 == 1) {
                formattedData.setCharAt(i, ' ');
            }
        }
        return formattedData.toString();
    }

    public String generateString(String str) {
        return new String(new char[number]).replace("\0", str);
    }
}

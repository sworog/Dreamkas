package project.lighthouse.autotests.steps.departmentManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.StaticDataCollections;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.pages.commercialManager.product.ProductApi;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffApi;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffListPage;
import project.lighthouse.autotests.pages.departmentManager.writeOff.WriteOffPage;

import java.io.IOException;

public class WriteOffSteps extends ScenarioSteps {

    WriteOffPage writeOffPage;
    CommonPage commonPage;
    WriteOffListPage writeOffListPage;
    WriteOffApi writeOffApi;
    ProductApi productApi;

    public WriteOffSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void createWriteOffThroughPost(String writeOffNumber) throws IOException, JSONException {
        writeOffApi.createWriteOffThroughPost(writeOffNumber);
    }

    @Step
    public void createWriteOffThroughPost(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                          String quantity, String price, String cause)
            throws IOException, JSONException {
        createProduct(productSku, productName, productBarCode, productUnits, purchasePrice);
        writeOffApi.createWriteOffThroughPost(writeOffNumber, productSku, quantity, price, cause);
    }

    @Step
    public void createWriteOffAndNavigateToIt(String writeOffNumber, String productName, String productSku, String productBarCode, String productUnits, String purchasePrice,
                                              String quantity, String price, String cause)
            throws JSONException, IOException {
        createProduct(productSku, productName, productBarCode, productUnits, purchasePrice);
        writeOffApi.createWriteOffAndNavigateToIt(writeOffNumber, productSku, quantity, price, cause);
    }

    public void createProduct(String productSku, String productName, String productBarCode, String productUnits, String purchasePrice) throws IOException, JSONException {
        if (!StaticDataCollections.products.containsKey(productSku)) {
            productApi.сreateProductThroughPost(productSku, productName, productBarCode, productUnits, purchasePrice);
        }
    }

    @Step
    public void createWriteOffAndNavigateToIt(String writeOffNumber)
            throws JSONException, IOException {
        writeOffApi.createWriteOffAndNavigateToIt(writeOffNumber);
    }

    @Step
    public void navigatoToWriteOffPage(String writeOffNumber) throws JSONException {
        writeOffApi.navigatoToWriteOffPage(writeOffNumber);
    }


    @Step
    public void openPage() {
        writeOffPage.open();
    }

    @Step
    public void input(String elementName, String inputText) {
        writeOffPage.input(elementName, inputText);
    }

    @Step
    public void continueWriteOffCreation() {
        writeOffPage.continueWriteOffCreation();
    }

    @Step
    public void addProductToWriteOff() {
        writeOffPage.addProductToWriteOff();
    }

    @Step
    public void checkCardValue(String elementName, String expectedValue) {
        writeOffPage.checkCardValue(elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String checkType, String elementName, String expectedValue) {
        writeOffPage.checkCardValue(checkType, elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String checkType, ExamplesTable checkValuesTable) {
        writeOffPage.checkCardValue(checkType, checkValuesTable);
    }

    @Step
    public void itemCheck(String value) {
        writeOffPage.itemCheck(value);
    }

    @Step
    public void itemCheckIsNotPresent(String value) {
        writeOffPage.itemCheckIsNotPresent(value);
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        writeOffPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocator(String value, ExamplesTable checkValuesTable) {
        writeOffPage.checkListItemHasExpectedValueByFindByLocator(value, checkValuesTable);
    }

    @Step
    public void itemDelete(String value) {
        writeOffPage.itemDelete(value);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = commonPage.generateTestData(charNumber);
        input(elementName, generatedData);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        writeOffPage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void writeOffStopEditButtonClick() {
        writeOffPage.writeOffStopEditButtonClick();
    }

    @Step
    public void writeOffStopEditlinkClick() {
        writeOffPage.writeOffStopEditlinkClick();
    }

    @Step
    public void editButtonClick() {
        writeOffPage.editButtonClick();
    }

    @Step
    public void elementClick(String elementName) {
        writeOffPage.elementClick(elementName);
    }

    @Step
    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        writeOffPage.childrentItemClickByFindByLocator(parentElementName, elementName);
    }

    @Step
    public void writeOffListPageOpen() {
        writeOffListPage.open();
    }

    @Step
    public void checkListItemHasExpectedValueByFindByLocatorInList(String value, String elementName, String expectedValue) {
        writeOffListPage.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Step
    public void listItemCheck(String value) {
        writeOffListPage.listItemCheck(value);
    }

    @Step
    public void writeOffItemListCreate() {
        writeOffListPage.writeOffItemListCreate();
    }

    @Step
    public void goToTheWriteOffListPage() {
        writeOffListPage.goToTheWriteOffListPage();
    }
}

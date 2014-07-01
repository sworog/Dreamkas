package project.lighthouse.autotests.steps.commercialManager.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import org.openqa.selenium.Alert;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.TimeoutException;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.helper.file.FileCreator;
import project.lighthouse.autotests.helper.file.FileDownloader;
import project.lighthouse.autotests.helper.file.FilesCompare;
import project.lighthouse.autotests.objects.web.supplier.SupplierObject;
import project.lighthouse.autotests.objects.web.supplier.SupplierObjectCollection;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierListPage;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierPage;

import java.io.File;

public class SupplierSteps extends ScenarioSteps {

    SupplierPage supplierPage;
    SupplierListPage supplierListPage;

    private File file;
    private String fileName;

    private String supplierName;

    @Step
    public void openSupplierCreatePage() {
        supplierPage.open();
    }

    @Step
    public void openSupplierListPage() {
        supplierListPage.open();
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        supplierPage.inputTable(examplesTable);
    }

    @Step
    public void input(String elementName, String value) {
        supplierPage.input(elementName, value);
    }

    @Step
    public void createButtonClick() {
        supplierPage.getCreateButtonFacade().click();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void cancelButtonClick() {
        supplierPage.getCancelButtonLinkFacade().click();
    }

    @Step
    public void labelsCheck(String elementName) {
        supplierPage.checkFieldLabel(elementName);
    }

    @Step
    public void checkFieldLength(String elementName, int number) {
        supplierPage.checkFieldLength(elementName, number);
    }

    @Step
    public void generateString(String elementName, int number) {
        String generatedData = new StringGenerator(number).generateTestData();
        supplierPage.input(elementName, generatedData);
        supplierName = generatedData;
    }

    @Step
    public void contains(String locator) {
        supplierListPage.getSupplierObjectCollection().contains(locator);
    }

    @Step
    public void supplierObjectCollectionNotContains(String locator) {
        SupplierObjectCollection supplierObjectCollection = null;
        try {
            supplierObjectCollection = supplierListPage.getSupplierObjectCollection();
        } catch (TimeoutException ignored) {
            supplierPage.containsText("Нет поставщиков");
        } finally {
            if (supplierObjectCollection != null) {
                supplierObjectCollection.notContains(locator);
            }
        }
    }

    @Step
    public void supplierCollectionObjectClickByLocator(String locator) {
        supplierListPage.getSupplierObjectCollection().clickByLocator(locator);
    }

    @Step
    public void supplierObjectCollectionContainsStoredValue() {
        supplierListPage.getSupplierObjectCollection().contains(supplierName);
    }

    @Step
    public void assertUploadFileButtonIsClickable() {
        supplierPage.getWaiter().elementToBeClickable(
                supplierPage.getUploadForm().getUploadButton().getFindBy());
    }

    @Step
    public void assertReplaceFileButtonIsClickable() {
        supplierPage.getWaiter().elementToBeClickable(
                supplierPage.getUploadForm().getReplaceFileButton().getFindBy());
    }

    @Step
    public void uploadFile(String fileName, int size) {
        File file = new FileCreator(fileName, size).create();
        supplierPage.getUploadForm().uploadFile(file);
        this.file = file;
        this.fileName = fileName;
    }

    @Step
    public void assertCreateButtonIsDisabled() {
        if (!supplierPage.getCreateButtonFacade().isDisabled()) {
            Assert.fail("The supplier create button is not disabled");
        }
    }

    @Step
    public void assertUploadButtonIsDisabled() {
        if (!supplierPage.getUploadForm().getUploadButton().isDisabled()) {
            Assert.fail("The upload button is not disabled");
        }
    }

    @Step
    public void assertReplaceButtonIsDisabled() {
        if (!supplierPage.getUploadForm().getReplaceFileButton().isDisabled()) {
            Assert.fail("The supplier cancel button is not disabled");
        }
    }

    @Step
    public void assertDeleteButtonIsDisabled() {
        if (!supplierPage.getUploadForm().getDeleteFileButton().isDisabled()) {
            Assert.fail("The supplier upload delete button is not disabled");
        }
    }

    @Step
    public void uploadDeleteButtonClick() {
        supplierPage.getUploadForm().getDeleteFileButton().click();
        Alert alert = supplierPage.getAlert();
        String alertMessage = "Вы уверены, что хотите удалить файл?";
        Assert.assertEquals(alertMessage, alert.getText());
        alert.accept();
    }

    @Step
    public void waitForUploadComplete() {
        supplierPage.getUploadForm().waitForUploadComplete();
    }

    @Step
    public void assertUploadedFileName() {
        assertUploadedFileName(supplierPage.getUploadForm().getUploadedFileName());
    }

    @Step
    public void assertUploadedFileName(String fileName) {
        Assert.assertEquals(fileName, supplierPage.getUploadForm().getUploadedFileName());
    }

    @Step
    public void assertDownloadedFileEqualsToUploadedFileOnTheSupplierPage() throws Exception {
        assertDownloadedFileEqualsToUploadedFile(
                supplierPage.getUploadForm().getUploadedFileNameLinkWebElement());
    }

    @Step
    private void assertDownloadedFileEqualsToUploadedFile(WebElement element) throws Exception {
        String downloadLocation = element.getAttribute("href");
        File downloadedFile = new FileDownloader(downloadLocation, fileName).getFile();
        Boolean compareResult = new FilesCompare(file, downloadedFile).compare();
        if (!compareResult) {
            Assert.fail("md5 sum is not equals!");
        }
    }

    @Step
    public void assertDownLoadedAgreementFileIsEqualsToUploadedFileOnTheSupplierList(String locator) throws Exception {
        assertDownloadedFileEqualsToUploadedFile(
                getDownloadAgreementButtonFromSupplierObjectByLocator(locator));
    }

    @Step
    public void assertDownloadAgreementButtonIsVisibleFromSupplierObjectByLocator(String locator) {
        supplierListPage.getWaiter().getVisibleWebElement(
                getDownloadAgreementButtonFromSupplierObjectByLocator(locator));
    }

    @Step
    public void assertDownloadAgreementButtonIsNotVisibleFromSupplierObjectByLocator(String locator) {
        try {
            WebElement downloadAgreementButtonWebElement =
                    getDownloadAgreementButtonFromSupplierObjectByLocator(locator);
            if (!supplierPage.invisibilityOfElementLocated(downloadAgreementButtonWebElement)) {
                String message =
                        String.format(
                                "The download link should not be visible in supplier object with locator %s",
                                locator);
                Assert.fail(message);
            }
        } catch (NoSuchElementException ignored) {
        }
    }

    @Step
    public void assertDownLoadAgreementButtonIsClickable(String locator) {
        supplierListPage.getWaiter().elementToBeClickable(
                getDownloadAgreementButtonFromSupplierObjectByLocator(locator));
    }

    @Step
    private WebElement getDownloadAgreementButtonFromSupplierObjectByLocator(String locator) {
        return ((SupplierObject) supplierListPage
                .getSupplierObjectCollection()
                .getAbstractObjectByLocator(locator))
                .getDownloadAgreementButtonWebElement();
    }

    @Step
    public void assertThereIsNoFileAttached() {
        try {
            supplierPage.getUploadForm().getUploadedFileNameLinkWebElement();
        } catch (TimeoutException ignored) {
        }
    }

    @Step
    public void assertFieldErrorMessage(String elementName, String expectedErrorMessage) {
        supplierPage.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(expectedErrorMessage);
    }
}

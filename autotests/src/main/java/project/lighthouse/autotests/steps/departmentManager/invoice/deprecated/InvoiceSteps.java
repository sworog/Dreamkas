package project.lighthouse.autotests.steps.departmentManager.invoice.deprecated;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.By;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.elements.preLoader.CheckBoxPreloader;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.pages.departmentManager.invoice.deprecated.InvoiceBrowsing;

public class InvoiceSteps extends ScenarioSteps {

    InvoiceBrowsing invoiceBrowsing;

    @Step
    public void openInvoiceListPage() throws JSONException {
        Store store = StaticData.stores.get(Store.DEFAULT_NUMBER);
        openStoreInvoiceListPage(store);
    }

    @Step
    public void openStoreInvoiceListPage(Store store) throws JSONException {
        String invoiceListPageUrl = String.format(
                "%s/stores/%s/invoices",
                UrlHelper.getWebFrontUrl(),
                store.getId());
        getDriver().navigate().to(invoiceListPageUrl);
    }

    @Step
    public void acceptChangesButtonClick() throws InterruptedException {
        invoiceBrowsing.acceptChangesButtonClick();
    }

    @Step
    public void discardChangesButtonClick() {
        invoiceBrowsing.discardChangesButtonClick();
    }

    @Step
    public void acceptDeleteButtonClick() throws InterruptedException {
        invoiceBrowsing.acceptDeleteButtonClick();
    }

    @Step
    public void discardDeleteButtonClick() {
        invoiceBrowsing.discardDeleteButtonClick();
    }

    @Step
    public void objectPropertyClick(String objectLocator, String objectPropertyName) {
        invoiceBrowsing.getInvoiceProductsCollection().clickPropertyByLocator(objectLocator, objectPropertyName);
    }

    @Step
    public void objectPropertyInput(String locator, String propertyName, String value) {
        invoiceBrowsing.getInvoiceProductsCollection().inputPropertyByLocator(locator, propertyName, value);
    }

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        invoiceBrowsing.getInvoiceProductsCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void itemClick(String itemName) {
        invoiceBrowsing.itemClick(itemName);
    }

    @Step
    public void checkTheStateOfCheckBox(String itemName, String state) {
        String checkBoxState = invoiceBrowsing.getItemAttribute(itemName, "checked");
        switch (state) {
            case "checked":
                if (checkBoxState != null) {
                    if (!checkBoxState.equals("true")) {
                        Assert.fail("CheckBox is not checked!");
                    }
                } else {
                    Assert.fail("CheckBox is not checked!");
                }
                break;
            case "unChecked":
                if (checkBoxState != null) {
                    Assert.fail("CheckBox is not unchecked!");
                }
                break;
        }
    }

    @Step
    public void checkTheCheckBoxText(String itemName, String text) {
        String actualText = invoiceBrowsing.getItems().get(itemName).getVisibleWebElement().findElement(By.xpath(".//..")).getText();
        Assert.assertEquals(text, actualText);
    }

    @Step
    public void checkBoxPreLoaderWait() {
        new CheckBoxPreloader(getDriver()).await();
    }
}

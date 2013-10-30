package project.lighthouse.autotests.pages.departmentManager.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;
import project.lighthouse.autotests.elements.PreLoader;
import project.lighthouse.autotests.objects.notApi.InvoiceSearchObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

public class InvoiceSearchPage extends CommonPageObject {

    public InvoiceSearchPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("skuOrSupplierInvoiceSku", new Input(this, "skuOrSupplierInvoiceSku"));
    }

    public void searchButtonClick() {
        new ButtonFacade(getDriver(), "Найти").click();
        new PreLoader(getDriver()).await();
    }

    private List<WebElement> getInvoiceSearchWebElements() {
        return waiter.getVisibleWebElements(By.xpath("//*[@class='invoiceList__item']"));
    }

    private List<InvoiceSearchObject> getInvoiceSearchObjects() {
        List<InvoiceSearchObject> invoiceSearchObjects = new ArrayList<>();
        for (WebElement element : getInvoiceSearchWebElements()) {
            InvoiceSearchObject invoiceSearchObject = new InvoiceSearchObject(getDriver(), element);
            invoiceSearchObjects.add(invoiceSearchObject);
        }
        return invoiceSearchObjects;
    }

    public HashMap<String, InvoiceSearchObject> getInvoiceSearchObjectHashMap() {
        HashMap<String, InvoiceSearchObject> invoiceSearchObjectHashMap = new HashMap<>();
        for (InvoiceSearchObject invoiceSearchObject : getInvoiceSearchObjects()) {
            invoiceSearchObjectHashMap.put(invoiceSearchObject.getSku(), invoiceSearchObject);
        }
        return invoiceSearchObjectHashMap;
    }

    public String getFormResultsText() {
        return waiter.getVisibleWebElement(By.xpath("//*[@class='form__results']/h3")).getText();
    }

    public void searchResultClick(String sku) {
        getInvoiceSearchObjectHashMap().get(sku).click();
    }

    public List<String> getHighlightTexts() {
        List<String> highlightsList = new ArrayList<>();
        for (WebElement element : waiter.getVisibleWebElements(By.xpath("//*[@class='page__highlighted']"))) {
            highlightsList.add(element.getText());
        }
        return highlightsList;
    }
}

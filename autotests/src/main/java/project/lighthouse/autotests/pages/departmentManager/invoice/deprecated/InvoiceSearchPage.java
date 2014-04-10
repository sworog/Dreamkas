package project.lighthouse.autotests.pages.departmentManager.invoice.deprecated;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.objects.web.search.InvoiceSearchObjectCollection;

public class InvoiceSearchPage extends CommonPageObject {

    public InvoiceSearchPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("skuOrSupplierInvoiceSku", new Input(this, "skuOrSupplierInvoiceSku"));
    }

    public void searchButtonClick() {
        new ButtonFacade(this, "Найти").click();
        new PreLoader(getDriver()).await();
    }

    public InvoiceSearchObjectCollection getInvoiceSearchObjectCollection() {
        return new InvoiceSearchObjectCollection(getDriver(), By.xpath("//*[@class='invoiceList__item']"));
    }

    public String getFormResultsText() {
        return findVisibleElement(By.xpath("//*[@class='form__results']//h3")).getText();
    }
}

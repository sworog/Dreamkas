package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.objects.web.invoice.InvoiceObjectCollection;

@DefaultUrl("/stockMovement")
public class InvoiceListPage extends BootstrapPageObject {

    public InvoiceListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Принять от поставщика").click();
    }

    @Override
    public void createElements() {
        put("filterTypes", new SelectByVisibleText(this, "filterTypes"));
    }

    public InvoiceObjectCollection getInvoiceObjectCollection() {
        return new InvoiceObjectCollection(getDriver());
    }
}

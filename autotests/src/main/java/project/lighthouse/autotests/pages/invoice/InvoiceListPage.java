package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;

@DefaultUrl("/?invoice/list")
public class InvoiceListPage extends PageObject{
    public InvoiceListPage(WebDriver driver) {
        super(driver);
    }
}

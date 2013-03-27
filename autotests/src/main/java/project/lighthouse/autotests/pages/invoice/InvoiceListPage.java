package project.lighthouse.autotests.pages.invoice;

import com.opera.core.systems.OperaWebElement;
import com.opera.core.systems.testing.drivers.MockOperaDriver;
import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.ICommonViewInterface;
import project.lighthouse.autotests.pages.common.ICommonPage;
import project.lighthouse.autotests.pages.common.ICommonView;
import project.lighthouse.autotests.pages.product.ProductCreatePage;

@DefaultUrl("/?invoice/list")
public class InvoiceListPage extends InvoiceCreatePage{

    @FindBy(name = "invoice")
    private WebElement invoiceListItem;

    @FindBy(xpath = "//*[@lh_button='create']")
    private WebElement invoiceListItemCreate;

    private static final String XPATH = "//*[@name='invoice']/*[@name='sku' and text()='%s']/..";
    ICommonViewInterface iCommonViewInterface = new ICommonView(getDriver(), XPATH, invoiceListItem);

    public InvoiceListPage(WebDriver driver) {
        super(driver);
    }

    public void  InvoiceListItemCreate(){
        $(invoiceListItemCreate).click();
    }

    public void ListItemClick(String skuValue){
        iCommonViewInterface.ItemClick(skuValue);
    }

    public void ListItemCheck(String skuValue){
        iCommonViewInterface.ItemCheck(skuValue);
    }

    public void CheckInvoiceListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue){
        iCommonViewInterface.CheckInvoiceListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }
}

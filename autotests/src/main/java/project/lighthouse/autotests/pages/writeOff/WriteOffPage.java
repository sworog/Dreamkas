package project.lighthouse.autotests.pages.writeOff;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.pages.elements.*;
import project.lighthouse.autotests.pages.invoice.InvoiceBrowsing;

import java.util.Map;

@DefaultUrl("/writeOff/create")
public class WriteOffPage extends InvoiceBrowsing {

    public static final String ITEM_NAME = "writeOffProduct";
    private static final String ITEM_SKU_NAME = "productSku";

    private static final String XPATH_PATTERN = "//*[@class='writeOff__dataInput']/*[@name='%s']";
    private static final String XPATH_AC_PATTERN = "//*[@class='writeOff__dataInput']/*[@lh_product_autocomplete='%s']";

    CommonViewInterface commonViewInterface = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    public WriteOffPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("writeOff number", new Input(this, "number"));
        items.put("writeOff date", new Date(this, "date"));

        items.put("writeOff number review", new NonType(this, "number"));
        items.put("writeOff date review", new NonType(this, "date"));

        items.put("inline writeOff number", new Input(this, By.xpath(String.format(XPATH_PATTERN, "number"))));
        items.put("inline writeOff date", new Date(this, By.xpath(String.format(XPATH_PATTERN, "date"))));

        items.put("writeOff product name autocomplete", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='name']")));
        items.put("writeOff product sku autocomplete", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='sku']")));
        items.put("writeOff product barCode autocomplete", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='barcode']")));

        items.put("writeOff product name review", new NonType(this, "productName"));
        items.put("writeOff product sku review", new NonType(this, "productSku"));
        items.put("writeOff product barCode review", new NonType(this, "productBarcode"));

        items.put("inline writeOff product name autocomplete", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "name"))));
        items.put("inline writeOff product sku autocomplete", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "sku"))));
        items.put("inline writeOff product barCode autocomplete", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "barcode"))));

        items.put("writeOff product quantity", new Input(this, "quantity"));
        items.put("writeOff product price", new Input(this, "price"));
        items.put("writeOff cause", new Textarea(this, "cause"));

        items.put("writeOff product quantity review", new NonType(this, "productAmount"));
        items.put("writeOff product price review", new NonType(this, "productPrice"));
        items.put("writeOff cause review", new NonType(this, "productCause"));

        items.put("inline writeOff product quantity", new Input(this, By.xpath(String.format(XPATH_PATTERN, "quantity"))));
        items.put("inline writeOff product price", new Input(this, By.xpath(String.format(XPATH_PATTERN, "price"))));
        items.put("inline writeOff cause", new Textarea(this, By.xpath(String.format(XPATH_PATTERN, "cause"))));

        items.put("writeOff product sum", new NonType(this, ""));
        items.put("writeOff product units", new NonType(this, "productUnits"));

        items.put("totalProducts", new NonType(this, "totalProducts"));
        items.put("totalSum", new NonType(this, "totalSum"));
    }

    public void continueWriteOffCreation() {
        String className = "button button_color_blue";
        String xpath = String.format("//*[@class='%s']/input", className);
        findElement(By.xpath(xpath)).click();
        waiter.waitUntilIsNotVisible(By.xpath(String.format("//*[@class='%s preloader preloader_rows']", className)));
    }

    public void addProductToWriteOff() {
        String className = "button button_color_blue writeOff__addMoreProduct";
        String addProductToWriteOffXpath = String.format("//*[@class='%s']/input", className);
        findElement(By.xpath(addProductToWriteOffXpath)).click();
        waiter.waitUntilIsNotVisible(By.xpath(String.format("//*[@class='%s preloader']", className)));
    }

    public void itemCheck(String value) {
        commonViewInterface.itemCheck(value);
    }

    public void itemCheckIsNotPresent(String value) {
        commonViewInterface.itemCheckIsNotPresent(value);
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        By findBy = items.get(elementName).getFindBy();
        commonViewInterface.checkListItemHasExpectedValueByFindByLocator(value, elementName, findBy, expectedValue);
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("expectedValue");
            checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
        }
    }

    public void itemDelete(String value) {
        String deleteButtonXpath = "//*[@class='writeOff__removeLink']";
        commonViewInterface.childrenItemNavigateAndClickByFindByLocator(value, By.xpath(deleteButtonXpath));
    }

    @Override
    public void writeOffStopEditButtonClick() {
        String xpath = "//*[@class='button writeOff__stopEditButton']";
        findVisibleElement(By.xpath(xpath)).click();
    }

    @Override
    public void writeOffStopEditlinkClick() {
        String xpath = "//*[@class='page__controlsLink writeOff__stopEditLink']";
        findVisibleElement(By.xpath(xpath)).click();
    }

    @Override
    public void editButtonClick() {
        String xpath = "//*[@class='page__controlsLink writeOff__editLink']";
        findVisibleElement(By.xpath(xpath)).click();
    }

    @Override
    public void elementClick(String elementName) {
        commonActions.elementClick(elementName);
    }

    @Override
    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        By findBy = items.get(parentElementName).getFindBy();
        commonViewInterface.childrentItemClickByFindByLocator(elementName, findBy);
    }
}

package project.lighthouse.autotests.pages.departmentManager.writeOff;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonViewInterface;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.*;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.objects.web.writeOff.WriteOffProductCollection;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoiceBrowsing;

import java.util.Map;

@DefaultUrl("/writeOffs/create")
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
        put("writeOff number", new Input(this, "number"));
        put("writeOff date", new DateTime(this, "date"));

        put("writeOff number review", new NonType(this, "number"));
        put("writeOff date review", new NonType(this, "date"));

        put("inline writeOff number", new Input(this, By.xpath(String.format(XPATH_PATTERN, "number"))));
        put("inline writeOff date", new DateTime(this, By.xpath(String.format(XPATH_PATTERN, "date")), "date"));

        put("writeOff product name autocomplete", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='name']")));
        put("writeOff product sku autocomplete", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='sku']")));
        put("writeOff product barCode autocomplete", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='barcode']")));

        put("writeOff product name review", new NonType(this, "productName"));
        put("writeOff product sku review", new NonType(this, "productSku"));
        put("writeOff product barCode review", new NonType(this, "productBarcode"));

        put("inline writeOff product name autocomplete", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "name"))));
        put("inline writeOff product sku autocomplete", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "sku"))));
        put("inline writeOff product barCode autocomplete", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "barcode"))));

        put("writeOff product quantity", new Input(this, "quantity"));
        put("writeOff product price", new Input(this, "price"));
        put("writeOff cause", new Textarea(this, "cause"));

        put("writeOff product quantity review", new NonType(this, "productAmount"));
        put("writeOff product price review", new NonType(this, "productPrice"));
        put("writeOff cause review", new NonType(this, "productCause"));

        put("inline writeOff product quantity", new Input(this, By.xpath(String.format(XPATH_PATTERN, "quantity"))));
        put("inline writeOff product price", new Input(this, By.xpath(String.format(XPATH_PATTERN, "price"))));
        put("inline writeOff cause", new Textarea(this, By.xpath(String.format(XPATH_PATTERN, "cause"))));

        put("writeOff product sum", new NonType(this, ""));
        put("writeOff product units", new NonType(this, "productUnits"));

        put("totalProducts", new NonType(this, "totalProducts"));
        put("totalSum", new NonType(this, "totalSum"));
    }

    public void continueWriteOffCreation() {
        new ButtonFacade(this, "Сохранить и перейти к добавлению товаров").click();
        new PreLoader(getDriver()).await();
    }

    public void addProductToWriteOff() {
        new ButtonFacade(this, "Добавить товар").click();
        new PreLoader(getDriver()).await();
    }

    public void itemCheck(String value) {
        commonViewInterface.itemCheck(value);
    }

    public void itemCheckIsNotPresent(String value) {
        commonViewInterface.itemCheckIsNotPresent(value);
    }

    @Deprecated
    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        By findBy = getItems().get(elementName).getFindBy();
        commonViewInterface.checkListItemHasExpectedValueByFindByLocator(value, elementName, findBy, expectedValue);
    }

    @Deprecated
    public void checkListItemHasExpectedValueByFindByLocator(String value, ExamplesTable checkValuesTable) {
        for (Map<String, String> row : checkValuesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("value");
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
        itemClick(elementName);
    }

    @Override
    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        By findBy = getItems().get(parentElementName).getFindBy();
        commonViewInterface.childrenItemClickByFindByLocator(elementName, findBy);
    }

    public WriteOffProductCollection getWriteOffProductCollection() {
        return new WriteOffProductCollection(getDriver(), By.name("writeOffProduct"));
    }
}

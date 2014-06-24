package project.lighthouse.autotests.pages.commercialManager.catalog;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.InputOnlyVisible;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.pages.commercialManager.catalog.localNavigation.CatalogMenuNavigation;

import static junit.framework.Assert.fail;

@DefaultUrl("/catalog")
public class GroupPage extends CommonPageObject {

    public static final String GROUP = "group";
    public static final String CATEGORY = "category";
    public static final String SUBCATEGORY = "subCategory";

    /**
     * Page object element for catalog local menu navigation
     */
    @SuppressWarnings("unused")
    private CatalogMenuNavigation catalogMenuNavigation;

    public GroupPage(WebDriver driver) {
        super(driver);
    }

    public CatalogMenuNavigation getLocalMenuNavigationPageObjectElement() {
        return catalogMenuNavigation;
    }

    public void addNewButtonClick() {
        new ButtonFacade(this, "Добавить группу").click();
    }

    public void addNewButtonConfirmClick() {
        new ButtonFacade(this, "Подтвердить").catalogClick();
        new PreLoader(getDriver()).await();
    }

    @Override
    public void createElements() {
        put("name", new InputOnlyVisible(this, "name"));
    }

    public CommonItem getItem() {
        return getItems().get("name");
    }

    public void create(String name) {
        addNewButtonClick();
        getItem().setValue(name);
        addNewButtonConfirmClick();
    }

    public void check(String name) {
        String classTitleXpath = getItemXpath(name);
        findVisibleElement(By.xpath(classTitleXpath));
    }

    public String getItemXpath(String name) {
        String classXpath = "//*[@model='catalogGroup' and text()='%s']";
        return String.format(classXpath, name);
    }

    public void popUpMenuInteraction(String name) {
        String triangleItemXpath = getItemXpath(name) + "/../../*[contains(@class, 'editor__editLink')]";
        click(By.xpath(triangleItemXpath));
    }

    public void popUpMenuDelete() {
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='tooltip__controlLink tooltip__removeLink']")).click();
    }

    public void popUpMenuAccept() {
        addNewButtonConfirmClick();
    }

    public void popUpMenuCancel() {
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='tooltip__closeLink']")).click();
    }

    public void popUpMenuEdit() {
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='tooltip__controlLink tooltip__editLink']")).click();
    }

    public void popUpMenuProductCreate() {
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='tooltip__controlLink']")).click();
    }

    public void checkIsNotPresent(String name) {
        String itemXpath = getItemXpath(name);
        getWaiter().waitUntilIsNotVisible(By.xpath(itemXpath));
    }

    public void itemClick(String name) {
        String itemXpath = getItemXpath(name);
        findVisibleElement(By.xpath(itemXpath)).click();
    }

    public void checkItemParent(String item, String parent) {
        String xpath = String.format(
                "//*[@class='catalog__groupItem' and *[@class='catalog__groupTitle']//*[@model='catalogGroup' and text()='%s'] and *[@class='catalog__categoryList']//*[@model='catalogCategory' and text()='%s']]",
                parent, item);
        findVisibleElement(By.xpath(xpath));
    }

    public WebElement mainTab(String mainTabType) {
        switch (mainTabType) {
            case GROUP:
                return findOnlyVisibleWebElementFromTheWebElementsList(
                        By.xpath("//*[@rel='categoryList']")
                );
            case CATEGORY:
                return findOnlyVisibleWebElementFromTheWebElementsList(
                        By.xpath("//*[@rel='subCategoryList']")
                );
            case SUBCATEGORY:
                return findOnlyVisibleWebElementFromTheWebElementsList(
                        By.xpath("//*[@rel='productList']")
                );
            default:
                fail(
                        String.format("No such value '%s'", mainTabType)
                );
        }
        return null;
    }

    public WebElement propertiesTab(String propertiesTabType) {
        switch (propertiesTabType) {
            case GROUP:
                return findVisibleElement(
                        By.xpath("//*[@rel='groupProperties']")
                );
            case CATEGORY:
                return findVisibleElement(
                        By.xpath("//*[@rel='categoryProperties']")
                );
            case SUBCATEGORY:
                return findVisibleElement(
                        By.xpath("//*[@rel='subCategoryProperties']")
                );
            default:
                fail(
                        String.format("No such value '%s'", propertiesTabType)
                );
        }
        return null;
    }
}

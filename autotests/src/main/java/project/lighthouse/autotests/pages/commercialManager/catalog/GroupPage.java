package project.lighthouse.autotests.pages.commercialManager.catalog;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.Keys;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.InputOnlyVisible;
import project.lighthouse.autotests.elements.PreLoader;

import static junit.framework.Assert.fail;

@DefaultUrl("/catalog")
public class GroupPage extends CommonPageObject {

    public static final String GROUP = "group";
    public static final String CATEGORY = "category";
    public static final String SUBCATEGORY = "subCategory";


    public GroupPage(WebDriver driver) {
        super(driver);
    }

    public void addNewButtonClick() {
        new ButtonFacade(getDriver(), "Добавить группу").click();
    }

    public void startEditionButtonLinkClick() {
        findVisibleElement(By.xpath("//*[@class='page__controlsLink editor__on']")).click();
    }

    public void startEditButtonLinkClickIsNotPresent() {
        try {
            startEditionButtonLinkClick();
            fail("The edit button is present on catalog page!");
        } catch (Exception ignored) {
        }
    }

    public void stopEditionButtonLinkClick() {
        try {
            WebElement stopEditionButton = findVisibleElement(By.xpath("//*[@class='page__controlsLink editor__off']"));
            evaluateJavascript(
                    String.format("window.scrollTo(%s, %s)",
                            stopEditionButton.getLocation().getX(), stopEditionButton.getLocation().getY() - 50)
            );
            stopEditionButton.click();
        } catch (Exception e) {
            if (e.getMessage().contains("Element is not clickable at point")) {
                withAction().sendKeys(Keys.ESCAPE).build().perform();
                evaluateJavascript("window.scrollTo(0,0)");
                stopEditionButtonLinkClick();
            } else {
                throw e;
            }
        }
    }

    public void addNewButtonConfirmClick() {
        new ButtonFacade(getDriver(), "Подтвердить").catalogClick();
        new PreLoader(getDriver()).await();
    }

    @Override
    public void createElements() {
        items.put("name", new InputOnlyVisible(this, "name"));
    }

    public CommonItem getItem() {
        return items.get("name");
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
        commonActions.elementClick(By.xpath(triangleItemXpath));
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
        waiter.waitUntilIsNotVisible(By.xpath(itemXpath));
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

    public void checkFieldLength(String elementName, int fieldLength) {
        CommonItem item = items.get(elementName);
        commonPage.checkFieldLength(elementName, fieldLength, item.getOnlyVisibleWebElement());
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

    public void productsExportLinkClick() {
        findVisibleElement(By.xpath("//*[@class='page__controlsLink catalog__exportLink']")).click();
        commonPage.checkAlertText("Выгрузка началась");
    }

    public void productsExportLinkIsNotPresent() {
        try {
            productsExportLinkClick();
            fail("The products export link is present on catalog page");
        } catch (Exception ignored) {
        }
    }
}

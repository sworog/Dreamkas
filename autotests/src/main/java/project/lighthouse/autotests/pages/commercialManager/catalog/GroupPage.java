package project.lighthouse.autotests.pages.commercialManager.catalog;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.Keys;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.InputOnlyVisible;

@DefaultUrl("/catalog")
public class GroupPage extends CommonPageObject {

    @FindBy(xpath = "//*[@class='button button_color_blue catalog__addGroupLink editor__control']")
    WebElementFacade addNewGroupButton;

    @FindBy(xpath = "//*[@class='page__controlsLink editor__on']")
    WebElementFacade startEditionButtonLink;

    public static final String GROUP = "group";
    public static final String CATEGORY = "category";
    public static final String SUBCATEGORY = "subCategory";


    public GroupPage(WebDriver driver) {
        super(driver);
    }

    public void addNewButtonClick() {
        addNewGroupButton.click();
    }

    public void startEditionButtonLinkClick() {
        startEditionButtonLink.click();
    }

    public void startEditButtonLinkClickIsNotPresent() {
        try {
            startEditionButtonLinkClick();
            String errorMessage = "The edit button is present on catalog page!";
            throw new AssertionError(errorMessage);
        } catch (Exception e) {
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
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='button button_color_blue']")).click();
        preloaderWait();
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
        find(By.xpath(classTitleXpath)).shouldBeVisible();
    }

    public String getItemXpath(String name) {
        String classXpath = "//*[@model_name='catalogGroup' and text()='%s']";
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
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='form__field']/*[@class='button button_color_blue' and normalize-space(text())='Подтвердить']")).click();
        preloaderWait();
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
        findElement(By.xpath(itemXpath)).click();
    }

    public void checkItemParent(String item, String parent) {
        String xpath = String.format(
                "//*[@class='catalog__groupItem' and *[@class='catalog__groupTitle']//*[@model_name='catalogGroup' and text()='%s'] and *[@class='catalog__categoryList']//*[@model_name='catalogCategory' and text()='%s']]",
                parent, item);
        find(By.xpath(xpath)).shouldBeVisible();
    }

    public void checkFieldLength(String elementName, int fieldLength) {
        CommonItem item = items.get(elementName);
        commonPage.checkFieldLength(elementName, fieldLength, item.getOnlyVisibleWebElement());
    }

    public void preloaderWait() {
        String preloaderXpath = "//*[contains(@class, 'preloader')]";
        waiter.waitUntilIsNotVisible(By.xpath(preloaderXpath));
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
                String errorMessage = String.format("No such value '%s'", mainTabType);
                throw new AssertionError(errorMessage);
        }
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
                String errorMessage = String.format("No such value '%s'", propertiesTabType);
                throw new AssertionError(errorMessage);
        }
    }
}

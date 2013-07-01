package project.lighthouse.autotests.pages.commercialManager.catalog;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.Keys;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.InputOnlyVisible;

@DefaultUrl("/catalog")
public class CatalogPage extends CommonPageObject {

    @FindBy(xpath = "//*[@class='button button_color_blue catalog__addClassLink editor__control']")
    WebElementFacade addNewClassButton;

    @FindBy(xpath = "//*[@class='page__controlsLink editor__on']")
    WebElementFacade startEditionButtonLink;

    @FindBy(xpath = "//*[@class='page__controlsLink editor__off']")
    WebElementFacade stopEditionButtonLink;

    public CatalogPage(WebDriver driver) {
        super(driver);
    }

    public void addNewButtonClick() {
        addNewClassButton.click();
    }

    public void startEditionButtonLinkClick() {
        startEditionButtonLink.click();
    }

    public void stopEditionButtonLinkClick() {
        try {
            stopEditionButtonLink.click();
        } catch (Exception e) {
            if (e.getMessage().contains("Element is not clickable at point")) {
                withAction().sendKeys(Keys.ESCAPE).build().perform();
                stopEditionButtonLinkClick();
            } else {
                throw e;
            }
        }
    }

    public void addNewButtonConfirmClick() {
        findBy("//*[@class='button button_color_blue']/input").click();
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
        String classXpath = "//*[(@class='catalog__classLink' or @class='catalogClass__className') and text()='%s']";
        return String.format(classXpath, name);
    }

    public void popUpMenuInteraction(String name) {
        String triangleItemXpath = getItemXpath(name) + "/../a[contains(@class, 'editor__editLink')]";
        findElement(By.xpath(triangleItemXpath)).click();
    }

    public void popUpMenuDelete() {
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='tooltip__controlLink tooltip__removeLink']")).click();
    }

    public void popUpMenuAccept() {
        findBy("//*[@class='form__field']/*[@class='button button_color_blue' and normalize-space(text())='Подтвердить']/input").click();
        preloaderWait();
    }

    public void popUpMenuCancel() {
        findBy("//*[@class='tooltip__closeLink']").click();
    }

    public void popUpMenuEdit() {
        findOnlyVisibleWebElementFromTheWebElementsList(By.xpath("//*[@class='tooltip__controlLink tooltip__editLink']")).click();
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
                "//*[@class='catalog__classItem' and *[@class='catalog__classTitle']/a[text()='%s'] and *[@class='catalog__classGroupList']//a[text()='%s']]",
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

    public String getItemLinkXpath(String name) {
        String classLinkXpath = "//*[@class='catalogClass__listLink' and normalize-space(text())='%s']";
        return String.format(classLinkXpath, name);
    }

    public void itemLinkCheck(String name) {
        String itemLinkXpath = getItemLinkXpath(name);
        $(findVisibleElement(By.xpath(itemLinkXpath))).shouldBeVisible();
    }

    public void itemLinkClick(String name) {
        String itemLinkXpath = getItemLinkXpath(name);
        findBy(itemLinkXpath).click();
    }
}

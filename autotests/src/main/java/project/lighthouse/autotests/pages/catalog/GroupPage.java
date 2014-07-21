package project.lighthouse.autotests.pages.catalog;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

/**
 * Group page object
 */
public class GroupPage extends CommonPageObject {

    public GroupPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getGroupTitle() {
        return findVisibleElement(By.className("page-title")).getText();
    }

    public void editGroupIconClick() {
        // TODO Wrap to bootstrap element
        findVisibleElement(By.xpath("//*[@class='fa fa-edit']")).click();
    }

    public void longArrowBackLinkClick() {
        // TODO Wrap to bootstrap element
        findVisibleElement(By.xpath("//*[@class='fa fa-long-arrow-left']")).click();
    }
}

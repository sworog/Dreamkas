package project.lighthouse.autotests.fixtures;

import org.jbehave.core.model.ExamplesTable;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

public class Us_57_3_Fixture {

    private static final String SHOP_1 = "25573";
    private static final String PRODUCT_ID_1 = "25573";
    private static final String SUBCATEGORY_NAME = "defaultSubCategory-s25u573";

    private Us_57_4_Fixture us_57_4_fixture = new Us_57_4_Fixture();

    public Us_57_4_Fixture.TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet getTodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet() {
        return us_57_4_fixture.new TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndWeekAgoDataSet getTodayIsBiggerThanYesterdayAndWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsBiggerThanYesterdayAndWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet getTodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndWeekAgoDataSet getTodayIsSmallerThanYesterdayAndWeekAgoDataSet() {
        return us_57_4_fixture.new TodayIsSmallerThanYesterdayAndWeekAgoDataSet(SHOP_1, PRODUCT_ID_1);
    }

    public ExamplesTable getEmptyFixtureExampleTable() {
        return generateEmptyFixtureExampleTable(SUBCATEGORY_NAME);
    }

    private ExamplesTable generateEmptyFixtureExampleTable(String name) {
        List<Map<String, String>> mapList = new ArrayList<>();
        Map<String, String> shop1DataMap = new HashMap<>();
        shop1DataMap.put("name", name);
        shop1DataMap.put("todayValue", "0,00 р.");
        shop1DataMap.put("yesterdayValue", "0,00 р.");
        shop1DataMap.put("weekAgoValue", "0,00 р.");
        mapList.add(shop1DataMap);
        return new ExamplesTable("").withRows(mapList);
    }
}

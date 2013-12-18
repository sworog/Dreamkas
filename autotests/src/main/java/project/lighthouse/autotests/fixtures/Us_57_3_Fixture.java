package project.lighthouse.autotests.fixtures;

public class Us_57_3_Fixture {

    private static final String SHOP_1 = "25573";
    private static final String PRODUCT_ID_1 = "25573";

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
}

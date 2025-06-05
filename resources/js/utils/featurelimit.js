export const isQuotaEmpty = (user, type) =>{
    let limit = 0;
    const permissions = user.company.subscription.permission
    .filter((p) => p.permission == type + '_limit');
if (permissions.length > 0) {
    limit = permissions[0].value;
}
if (limit != '') {
    const usage = user.company.usages[type] ?? 0;
    console.log(`usage : ${usage}, limit : ${limit}`)
    return usage >= limit;
}
return false;
}